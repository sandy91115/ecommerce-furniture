<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Services\ImageUploadService;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\SeoMetadata;
use App\Models\User;
use App\Models\Vendor;
use App\Services\Seo\SeoMetadataService;
use App\Services\ProductExcelService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $productService;
    protected $imageService;

    public function __construct(ProductService $productService, ImageUploadService $imageService)
    {
        $this->productService = $productService;
        $this->imageService = $imageService;
    }

    public function index()
    {
        $this->authorize('products.view');

        $products = $this->productService->all();
        return view('admin.products.index', compact('products'));
    }

    public function export(ProductExcelService $excelService)
    {
        $this->authorize('products.view');

        return response($excelService->exportProducts(), 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="products-' . now()->format('Ymd-His') . '.xlsx"',
        ]);
    }

    public function importTemplate(ProductExcelService $excelService)
    {
        $this->authorize('products.create');

        return response($excelService->template(), 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="products-import-template.xlsx"',
        ]);
    }

    public function import(Request $request, ProductExcelService $excelService)
    {
        $this->authorize('products.create');

        $request->validate([
            'products_file' => 'required|file|mimes:xlsx|max:10240',
        ]);

        $result = $excelService->importProducts($request->file('products_file')->getRealPath());
        $message = "Product import complete: {$result['created']} created, {$result['updated']} updated, {$result['images']} images added.";

        return redirect()
            ->route('admin.products.index')
            ->with('success', $message)
            ->with('product_import_errors', $result['errors']);
    }

    public function create()
    {
        $this->authorize('products.create');

        $categories = $this->categoryOptions();
        $materials = \App\Models\Material::all();
        $colors = \App\Models\Color::all();
        // Single-vendor: no vendors dropdown
        return view('admin.products.create', compact('categories', 'materials', 'colors'));
    }

    public function store(ProductStoreRequest $request)
    {
        $this->authorize('products.create');

        $data = $request->validated();
        $reviewRows = $data['product_reviews'] ?? [];
        $seoMeta = $data['seo_meta'] ?? [];
        unset($data['product_reviews'], $data['seo_meta'], $data['images'], $data['image_alts'], $data['featured_image_index']);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data = $this->normalizeProductStructuredData($data);

        $product = $this->productService->create($data);
        $this->syncSeoMetadata($product, $seoMeta);
        $this->syncInlineReviews($product, $reviewRows);

        if ($request->hasFile('images')) {
            $this->imageService->queueProductImages(
                product: $product,
                files: $request->file('images', []),
                featuredIndex: $request->integer('featured_image_index', 0),
                altTexts: $request->input('image_alts', []),
            );
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $this->authorize('products.view');

        $product->load(['images', 'category', 'vendor']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->authorize('products.update');

        $product->load('reviews.user');
        $seoMetadata = SeoMetadata::query()
            ->where('seoable_type', Product::class)
            ->where('seoable_id', $product->id)
            ->first();
        $categories = $this->categoryOptions();
        $vendors = Schema::hasTable('vendors') ? Vendor::where('status', 'active')->get() : collect([]); // Single-vendor mode: empty vendors list
        $materials = \App\Models\Material::all();
        $colors = \App\Models\Color::all();
        return view('admin.products.edit', compact('product', 'categories', 'vendors', 'materials', 'colors', 'seoMetadata'));
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $this->authorize('products.update');

        $data = $request->validated();
        $reviewRows = $data['product_reviews'] ?? [];
        $deleteReviewIds = $data['delete_product_reviews'] ?? [];
        $seoMeta = $data['seo_meta'] ?? [];
        $imageMeta = $data['image_meta'] ?? [];
        $featuredExistingImageId = $data['featured_existing_image_id'] ?? null;
        unset(
            $data['product_reviews'],
            $data['delete_product_reviews'],
            $data['seo_meta'],
            $data['images'],
            $data['image_alts'],
            $data['replace_images'],
            $data['delete_image'],
            $data['featured_image_index'],
            $data['image_meta'],
            $data['featured_existing_image_id']
        );

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data = $this->normalizeProductStructuredData($data);

        $product = $this->productService->update($product->id, $data);

        if (!$product) {
            return back()->withErrors(['error' => 'Product update failed or not found.']);
        }

        $this->syncInlineReviews($product, $reviewRows, $deleteReviewIds);
        $this->syncSeoMetadata($product, $seoMeta);

        if ($request->boolean('replace_images')) {
            $this->imageService->deleteProductImages($product);
        } else {
            $deleteIds = collect($request->input('delete_image', []))
                ->filter(fn ($value) => filled($value))
                ->map(fn ($value) => (int) $value)
                ->unique()
                ->values();

            if ($deleteIds->isNotEmpty()) {
                $product->images()
                    ->whereIn('id', $deleteIds)
                    ->get()
                    ->each(fn ($image) => $this->imageService->deleteImage($image));
            }

            $this->syncImageSeoMetadata($product, $imageMeta, $featuredExistingImageId ? (int) $featuredExistingImageId : null, $deleteIds->all());
        }

        if ($request->hasFile('images')) {
            $this->imageService->queueProductImages(
                product: $product,
                files: $request->file('images', []),
                featuredIndex: $request->integer('featured_image_index', 0),
                altTexts: $request->input('image_alts', []),
            );
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('products.delete');

        $this->productService->delete($product->id);
        return redirect()->route('admin.products.index')->with('success', 'Product moved to Recycle Bin successfully.');
    }

    public function lowStock()
    {
        $this->authorize('products.view');

        $products = $this->productService->lowStock();
        return view('admin.products.low-stock', compact('products'));
    }

    public function quotationProducts()
    {
        $this->authorize('products.view');

        $products = Product::with(['category', 'vendor'])
            ->where('product_type', 'quotation')
            ->paginate(10);
        return view('admin.products.quotation-products', compact('products'));
    }

    private function categoryOptions()
    {
        $categories = Category::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $childrenByParent = $categories
            ->whereNotNull('parent_id')
            ->groupBy('parent_id');

        $buildOptions = function ($parentId = null, int $depth = 0) use (&$buildOptions, $categories, $childrenByParent) {
            $items = $parentId === null
                ? $categories->whereNull('parent_id')
                : ($childrenByParent->get($parentId) ?? collect());

            return $items->flatMap(function ($category) use (&$buildOptions, $depth) {
                $category->option_label = str_repeat('-- ', $depth) . $category->name;

                return collect([$category])
                    ->merge($buildOptions($category->id, $depth + 1));
            });
        };

        return $buildOptions()->values();
    }

    private function normalizeProductStructuredData(array $data): array
    {
        $data['technical_specifications'] = $this->filledRows(
            $data['technical_specifications'] ?? [],
            ['field', 'value', 'notes'],
            ['field', 'value']
        );

        $data['customization_options'] = $this->filledRows(
            $data['customization_options'] ?? [],
            ['category', 'choices', 'applies_to'],
            ['category', 'choices']
        );

        $data['faqs'] = $this->filledRows(
            $data['faqs'] ?? [],
            ['question', 'answer'],
            ['question', 'answer']
        );

        return $data;
    }

    private function filledRows(array $rows, array $keys, array $requiredKeys): array
    {
        return collect($rows)
            ->map(function ($row) use ($keys) {
                return collect($keys)
                    ->mapWithKeys(fn ($key) => [$key => trim((string) ($row[$key] ?? ''))])
                    ->all();
            })
            ->filter(function ($row) use ($requiredKeys) {
                foreach ($requiredKeys as $key) {
                    if ($row[$key] !== '') {
                        return true;
                    }
                }

                return false;
            })
            ->values()
            ->all();
    }

    private function syncInlineReviews(Product $product, array $reviewRows, array $deleteReviewIds = []): void
    {
        $defaultUserId = auth()->id() ?: User::query()->value('id');

        if (! empty($deleteReviewIds)) {
            $product->reviews()
                ->whereIn('id', collect($deleteReviewIds)->map(fn ($id) => (int) $id)->all())
                ->delete();
        }

        foreach ($reviewRows as $row) {
            $reviewId = (int) ($row['id'] ?? 0);
            $comment = trim((string) ($row['comment'] ?? ''));
            $title = trim((string) ($row['title'] ?? ''));
            $reviewerName = trim((string) ($row['reviewer_name'] ?? ''));
            $rating = isset($row['rating']) && $row['rating'] !== '' ? (int) $row['rating'] : null;

            if ($reviewId === 0 && $comment === '' && $title === '' && $reviewerName === '' && $rating === null) {
                continue;
            }

            $payload = [
                'product_id' => $product->id,
                'user_id' => $defaultUserId,
                'reviewer_name' => $reviewerName !== '' ? $reviewerName : 'Customer',
                'reviewer_email' => trim((string) ($row['reviewer_email'] ?? '')) ?: null,
                'rating' => $rating ?: 5,
                'title' => $title !== '' ? $title : Str::limit($comment !== '' ? $comment : 'Customer Review', 120, ''),
                'comment' => $comment,
                'status' => $row['status'] ?? 'approved',
            ];

            if ($reviewId > 0) {
                $review = $product->reviews()->whereKey($reviewId)->first();

                if ($review) {
                    $payload['user_id'] = $review->user_id ?: $defaultUserId;
                    $review->update($payload);
                }

                continue;
            }

            Review::create($payload);
        }
    }

    private function syncImageSeoMetadata(Product $product, array $imageMeta, ?int $featuredImageId = null, array $deletedImageIds = []): void
    {
        $deletedImageIds = collect($deletedImageIds)->map(fn ($id) => (int) $id)->all();

        foreach ($imageMeta as $imageId => $meta) {
            $imageId = (int) $imageId;

            if (in_array($imageId, $deletedImageIds, true)) {
                continue;
            }

            $product->images()
                ->whereKey($imageId)
                ->update([
                    'alt' => trim((string) ($meta['alt'] ?? '')) ?: null,
                    'title' => trim((string) ($meta['title'] ?? '')) ?: null,
                    'caption' => trim((string) ($meta['caption'] ?? '')) ?: null,
                    'description' => trim((string) ($meta['description'] ?? '')) ?: null,
                    'sort_order' => (int) ($meta['sort_order'] ?? 0),
                ]);
        }

        if ($featuredImageId && ! in_array($featuredImageId, $deletedImageIds, true)) {
            $ownsImage = $product->images()->whereKey($featuredImageId)->exists();

            if ($ownsImage) {
                $product->images()->update(['featured' => false]);
                $product->images()->whereKey($featuredImageId)->update(['featured' => true]);
            }
        }
    }

    private function syncSeoMetadata(Product $product, array $seoMeta): void
    {
        $fallback = [
            'title' => $product->seo_title,
            'description' => $product->seo_description,
            'schema_type' => 'Product',
        ];

        app(SeoMetadataService::class)->syncForModel($product, $seoMeta, $fallback);
    }

}
