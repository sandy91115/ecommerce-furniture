<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Color;
use App\Models\Material;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use SimpleXMLElement;
use Throwable;
use ZipArchive;

class ProductExcelService
{
    private const HEADERS = [
        'id',
        'name',
        'sku',
        'category_id',
        'category_name',
        'price',
        'sale_price',
        'tax_slab',
        'stock',
        'status',
        'product_type',
        'featured',
        'short_description',
        'description',
        'extra_title',
        'extra_description',
        'material_id',
        'material_name',
        'color_id',
        'color_name',
        'weight',
        'dimensions',
        'warranty_months',
        'assembly_required',
        'seo_title',
        'seo_description',
        'technical_specifications',
        'customization_options',
        'faqs',
        'product_rating',
        'product_rating_count',
        'care_and_maintenance',
        'shipping_details',
        'image_urls',
        'image_paths',
        'image_alts',
        'replace_images',
    ];

    private const HEADER_ALIASES = [
        'product_id' => 'id',
        'title' => 'name',
        'product_name' => 'name',
        'category' => 'category_name',
        'regular_price' => 'price',
        'tax' => 'tax_slab',
        'tax_rate' => 'tax_slab',
        'quantity' => 'stock',
        'type' => 'product_type',
        'is_featured' => 'featured',
        'material' => 'material_name',
        'color' => 'color_name',
        'rating' => 'product_rating',
        'rating_count' => 'product_rating_count',
        'care' => 'care_and_maintenance',
        'shipping' => 'shipping_details',
        'shipping_info' => 'shipping_details',
        'images' => 'image_urls',
        'image_url' => 'image_urls',
        'image_path' => 'image_paths',
        'image_alt' => 'image_alts',
    ];

    private const VALID_STATUSES = ['pending', 'active', 'rejected'];
    private const VALID_PRODUCT_TYPES = ['sell', 'quotation'];
    private const VALID_TAX_SLABS = ['0', 'nil_rate', '5', '18', '40', 'special_rates'];

    public function __construct(
        private ProductService $productService,
        private ImageUploadService $imageService,
    )
    {
    }

    public function exportProducts(): string
    {
        $rows = [self::HEADERS];

        Product::with(['category', 'material', 'color', 'images'])
            ->orderBy('id')
            ->chunk(200, function ($products) use (&$rows) {
                foreach ($products as $product) {
                    $rows[] = $this->productToRow($product);
                }
            });

        return $this->buildXlsx($rows);
    }

    public function template(): string
    {
        $category = Category::query()->orderBy('id')->first();
        $material = Material::query()->orderBy('id')->first();
        $color = Color::query()->orderBy('id')->first();

        return $this->buildXlsx([
            self::HEADERS,
            [
                '',
                'Sample Coffee Table',
                'SKU-001',
                $category?->id ?? '',
                $category?->name ?? '',
                '19999',
                '17999',
                '18',
                '10',
                'active',
                'sell',
                '1',
                'Short product summary for listing pages.',
                'Full product description for the detail page.',
                'Made for modern homes',
                'Optional extra details shown on quotation products.',
                $material?->id ?? '',
                $material?->name ?? '',
                $color?->id ?? '',
                $color?->name ?? '',
                '25.50',
                '{"length":120,"width":60,"height":45,"unit":"cm"}',
                '12',
                '0',
                'Sample SEO title',
                'Sample SEO description',
                '[{"field":"Frame","value":"Wood","notes":""}]',
                '[{"category":"Finish","choices":"Glossy, Matte","applies_to":"Top"}]',
                '[{"question":"Is it customizable?","answer":"Yes, customization is available."}]',
                '4.5',
                '12',
                'Clean with a soft dry cloth.',
                'Standard shipping timelines apply. Delivery charges may vary by location.',
                'https://example.com/product-image.jpg',
                'product-imports/sample-coffee-table.jpg',
                'Sample coffee table',
                '0',
            ],
        ]);
    }

    public function importProducts(string $path): array
    {
        $rows = $this->readXlsx($path);

        if (count($rows) < 2) {
            throw new RuntimeException('Excel file me product rows nahi mile.');
        }

        $headers = $this->normalizeHeaders($rows[0]);
        $created = 0;
        $updated = 0;
        $images = 0;
        $errors = [];

        foreach (array_slice($rows, 1) as $offset => $row) {
            $rowNumber = $offset + 2;

            if ($this->rowIsBlank($row)) {
                continue;
            }

            $data = $this->mapRow($headers, $row);

            try {
                $result = DB::transaction(fn () => $this->upsertProduct($data, $rowNumber));

                if ($result['action'] === 'created') {
                    $created++;
                } else {
                    $updated++;
                }

                try {
                    $images += $this->attachImages($result['product'], $data, $rowNumber);
                } catch (Throwable $exception) {
                    $errors[] = "Row {$rowNumber} images: {$exception->getMessage()}";
                }
            } catch (Throwable $exception) {
                $errors[] = "Row {$rowNumber}: {$exception->getMessage()}";
            }
        }

        return [
            'created' => $created,
            'updated' => $updated,
            'images' => $images,
            'errors' => $errors,
        ];
    }

    private function productToRow(Product $product): array
    {
        return [
            $product->id,
            $product->name,
            $product->sku,
            $product->category_id,
            $product->category?->name,
            $product->price,
            $product->sale_price,
            $product->tax_slab,
            $product->stock,
            $product->status,
            $product->product_type,
            $product->featured ? '1' : '0',
            $product->short_description,
            $product->description,
            $product->extra_title,
            $product->extra_description,
            $product->material_id,
            $product->material?->name,
            $product->color_id,
            $product->color?->name,
            $product->weight,
            $this->jsonForCell($product->dimensions),
            $product->warranty_months,
            $product->assembly_required ? '1' : '0',
            $product->seo_title,
            $product->seo_description,
            $this->jsonForCell($product->technical_specifications),
            $this->jsonForCell($product->customization_options),
            $this->jsonForCell($product->faqs),
            $product->product_rating,
            $product->product_rating_count,
            $product->care_and_maintenance,
            $product->shipping_details,
            '',
            $product->images->pluck('path')->filter()->implode(','),
            $product->images->pluck('alt')->filter()->implode('|'),
            '0',
        ];
    }

    private function upsertProduct(array $data, int $rowNumber): array
    {
        $id = $this->nullableInt($data['id'] ?? null, 'id');
        $skuFromRow = $this->cell($data, 'sku');
        $product = null;

        if ($id) {
            $product = Product::query()->find($id);

            if (! $product) {
                throw new RuntimeException("Product id {$id} nahi mila.");
            }
        } elseif ($skuFromRow !== '') {
            $product = Product::query()->where('sku', $skuFromRow)->first();
        }

        $name = $this->requiredValue($data, 'name', $product?->name, 'name');
        $sku = $this->requiredValue($data, 'sku', $product?->sku, 'sku');
        $categoryId = $this->resolveCategoryId($data, $product);
        $price = $this->requiredDecimal($data, 'price', $product?->price, 'price');
        $salePrice = $this->nullableDecimal($data['sale_price'] ?? null, 'sale_price');
        $stock = $this->requiredInt($data, 'stock', $product?->stock, 'stock');
        $taxSlab = $this->requiredValue($data, 'tax_slab', $product?->tax_slab, 'tax_slab');
        $status = strtolower($this->requiredValue($data, 'status', $product?->status ?? 'active', 'status'));
        $productType = strtolower($this->requiredValue($data, 'product_type', $product?->product_type ?? 'sell', 'product_type'));
        $shortDescription = $this->requiredValue($data, 'short_description', $product?->short_description, 'short_description');
        $description = $this->requiredValue($data, 'description', $product?->description, 'description');

        if (! in_array($status, self::VALID_STATUSES, true)) {
            throw new RuntimeException('status pending, active ya rejected hona chahiye.');
        }

        if (! in_array($productType, self::VALID_PRODUCT_TYPES, true)) {
            throw new RuntimeException('product_type sell ya quotation hona chahiye.');
        }

        if (! in_array($taxSlab, self::VALID_TAX_SLABS, true)) {
            throw new RuntimeException('tax_slab 0, nil_rate, 5, 18, 40 ya special_rates hona chahiye.');
        }

        if ($salePrice !== null && $salePrice >= $price) {
            throw new RuntimeException('sale_price regular price se kam hona chahiye.');
        }

        $this->ensureUniqueProductValue('sku', $sku, $product);
        $this->ensureUniqueProductValue('name', $name, $product);
        $this->ensureUniqueProductValue('slug', Str::slug($name), $product);

        $payload = [
            'category_id' => $categoryId,
            'name' => $name,
            'sku' => $sku,
            'price' => $price,
            'sale_price' => $salePrice,
            'tax_slab' => $taxSlab,
            'stock' => $stock,
            'status' => $status,
            'product_type' => $productType,
            'featured' => $this->booleanValue($data['featured'] ?? null, false),
            'short_description' => $shortDescription,
            'description' => $description,
            'extra_title' => $this->nullableString($data['extra_title'] ?? null),
            'extra_description' => $this->nullableString($data['extra_description'] ?? null),
            'material_id' => $this->resolveOptionalLookupId(Material::class, $data, 'material_id', 'material_name', 'material'),
            'color_id' => $this->resolveOptionalLookupId(Color::class, $data, 'color_id', 'color_name', 'color'),
            'weight' => $this->nullableDecimal($data['weight'] ?? null, 'weight'),
            'dimensions' => $this->decodeLooseJson($data['dimensions'] ?? null),
            'warranty_months' => $this->nullableInt($data['warranty_months'] ?? null, 'warranty_months'),
            'assembly_required' => $this->booleanValue($data['assembly_required'] ?? null, false),
            'seo_title' => $this->nullableString($data['seo_title'] ?? null),
            'seo_description' => $this->nullableString($data['seo_description'] ?? null),
            'technical_specifications' => $this->decodeJsonArray($data['technical_specifications'] ?? null, 'technical_specifications'),
            'customization_options' => $this->decodeJsonArray($data['customization_options'] ?? null, 'customization_options'),
            'faqs' => $this->decodeJsonArray($data['faqs'] ?? null, 'faqs'),
            'product_rating' => $this->nullableRating($data['product_rating'] ?? null),
            'product_rating_count' => $this->nullableInt($data['product_rating_count'] ?? null, 'product_rating_count'),
            'care_and_maintenance' => $this->nullableString($data['care_and_maintenance'] ?? null),
            'shipping_details' => $this->nullableString($data['shipping_details'] ?? null),
        ];

        if ($product) {
            $product = $this->productService->update($product->id, $payload);

            return ['action' => 'updated', 'product' => $product];
        }

        $product = $this->productService->create($payload);

        return ['action' => 'created', 'product' => $product];
    }

    private function attachImages(Product $product, array $data, int $rowNumber): int
    {
        $imageSources = collect($this->splitList($data['image_paths'] ?? ''))
            ->map(fn ($path) => ['type' => 'path', 'value' => $path])
            ->merge(
                collect($this->splitList($data['image_urls'] ?? ''))
                    ->map(fn ($url) => ['type' => 'url', 'value' => $url])
            )
            ->values();

        if ($imageSources->isEmpty()) {
            return 0;
        }

        if ($this->booleanValue($data['replace_images'] ?? null, false)) {
            $this->imageService->deleteProductImages($product);
        }

        $alts = $this->splitList($data['image_alts'] ?? '');
        $makeFirstFeatured = ! $product->images()->exists();
        $imported = 0;

        foreach ($imageSources as $index => $source) {
            [$sourcePath, $originalName, $deleteAfterImport] = $source['type'] === 'url'
                ? $this->downloadImage($source['value'], $rowNumber)
                : $this->resolveImagePath($source['value']);

            try {
                $image = $this->imageService->importProductImageFromPath(
                    product: $product,
                    sourcePath: $sourcePath,
                    originalName: $originalName,
                    isFeatured: $makeFirstFeatured && $imported === 0,
                    altText: $alts[$index] ?? $product->name,
                );

                if ($image) {
                    $imported++;
                }
            } finally {
                if ($deleteAfterImport) {
                    @unlink($sourcePath);
                }
            }
        }

        return $imported;
    }

    private function splitList(mixed $value): array
    {
        $value = trim((string) $value);

        if ($value === '') {
            return [];
        }

        return collect(preg_split('/[\r\n,|;]+/', $value) ?: [])
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->all();
    }

    private function downloadImage(string $url, int $rowNumber): array
    {
        if (! Str::startsWith($url, ['http://', 'https://'])) {
            throw new RuntimeException("image_urls me row {$rowNumber} par valid http/https URL hona chahiye.");
        }

        $response = Http::timeout(30)->get($url);

        if (! $response->successful()) {
            throw new RuntimeException("Image URL download failed [{$url}].");
        }

        $extension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION));
        $contentType = strtolower((string) $response->header('Content-Type'));

        if ($extension === '') {
            $extension = match (true) {
                str_contains($contentType, 'png') => 'png',
                str_contains($contentType, 'webp') => 'webp',
                default => 'jpg',
            };
        }

        $originalName = basename(parse_url($url, PHP_URL_PATH) ?: '') ?: 'product-image.' . $extension;

        if (! str_contains($originalName, '.')) {
            $originalName .= '.' . $extension;
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'product-image-url-');

        if ($tempPath === false || file_put_contents($tempPath, $response->body()) === false) {
            throw new RuntimeException("Image URL temporary file me save nahi ho payi [{$url}].");
        }

        return [$tempPath, $originalName, true];
    }

    private function resolveImagePath(string $path): array
    {
        $path = trim(str_replace('\\', '/', $path));

        if ($path === '') {
            throw new RuntimeException('image_paths blank hai.');
        }

        if ($this->isAbsolutePath($path) && is_file($path)) {
            return [$path, basename($path), false];
        }

        $relativePath = ltrim($path, '/');

        if (Str::startsWith($relativePath, 'storage/')) {
            $relativePath = substr($relativePath, strlen('storage/'));
        }

        $candidates = [
            Storage::disk('public')->path($relativePath),
            public_path($relativePath),
            public_path('storage/' . $relativePath),
            base_path($relativePath),
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return [$candidate, basename($candidate), false];
            }
        }

        throw new RuntimeException("Image path [{$path}] nahi mila. File storage/app/public ke andar ya valid local path par honi chahiye.");
    }

    private function isAbsolutePath(string $path): bool
    {
        return str_starts_with($path, '/') || preg_match('/^[A-Za-z]:\//', $path) === 1;
    }

    private function resolveCategoryId(array $data, ?Product $product): int
    {
        $categoryId = $this->nullableInt($data['category_id'] ?? null, 'category_id');

        if ($categoryId) {
            if (! Category::query()->whereKey($categoryId)->exists()) {
                throw new RuntimeException("category_id {$categoryId} nahi mila.");
            }

            return $categoryId;
        }

        $categoryName = $this->cell($data, 'category_name');

        if ($categoryName !== '') {
            $category = Category::query()->where('name', $categoryName)->first();

            if (! $category) {
                throw new RuntimeException("category_name '{$categoryName}' nahi mila.");
            }

            return $category->id;
        }

        if ($product?->category_id) {
            return $product->category_id;
        }

        throw new RuntimeException('category_id ya category_name required hai.');
    }

    private function resolveOptionalLookupId(string $modelClass, array $data, string $idKey, string $nameKey, string $label): ?int
    {
        $id = $this->nullableInt($data[$idKey] ?? null, $idKey);

        if ($id) {
            if (! $modelClass::query()->whereKey($id)->exists()) {
                throw new RuntimeException("{$idKey} {$id} nahi mila.");
            }

            return $id;
        }

        $name = $this->cell($data, $nameKey);

        if ($name === '') {
            return null;
        }

        $model = $modelClass::query()->where('name', $name)->first();

        if (! $model) {
            throw new RuntimeException("{$label} '{$name}' nahi mila.");
        }

        return $model->id;
    }

    private function ensureUniqueProductValue(string $column, string $value, ?Product $product): void
    {
        $query = Product::withTrashed()->where($column, $value);

        if ($product) {
            $query->whereKeyNot($product->id);
        }

        if ($query->exists()) {
            throw new RuntimeException("{$column} '{$value}' already use me hai.");
        }
    }

    private function requiredValue(array $data, string $key, mixed $fallback, string $label): string
    {
        $value = $this->cell($data, $key);

        if ($value !== '') {
            return $value;
        }

        $fallback = trim((string) $fallback);

        if ($fallback !== '') {
            return $fallback;
        }

        throw new RuntimeException("{$label} required hai.");
    }

    private function requiredDecimal(array $data, string $key, mixed $fallback, string $label): float
    {
        $value = $this->cell($data, $key);

        if ($value === '') {
            $value = trim((string) $fallback);
        }

        $number = $this->nullableDecimal($value, $label);

        if ($number === null) {
            throw new RuntimeException("{$label} required hai.");
        }

        if ($number < 0) {
            throw new RuntimeException("{$label} negative nahi ho sakta.");
        }

        return $number;
    }

    private function requiredInt(array $data, string $key, mixed $fallback, string $label): int
    {
        $value = $this->cell($data, $key);

        if ($value === '') {
            $value = trim((string) $fallback);
        }

        $number = $this->nullableInt($value, $label);

        if ($number === null) {
            throw new RuntimeException("{$label} required hai.");
        }

        if ($number < 0) {
            throw new RuntimeException("{$label} negative nahi ho sakta.");
        }

        return $number;
    }

    private function nullableDecimal(mixed $value, string $label): ?float
    {
        $value = str_replace([',', 'Rs.', '₹', '$'], '', trim((string) $value));

        if ($value === '') {
            return null;
        }

        if (! is_numeric($value)) {
            throw new RuntimeException("{$label} numeric hona chahiye.");
        }

        $number = (float) $value;

        if ($number < 0) {
            throw new RuntimeException("{$label} negative nahi ho sakta.");
        }

        return $number;
    }

    private function nullableInt(mixed $value, string $label): ?int
    {
        $value = str_replace(',', '', trim((string) $value));

        if ($value === '') {
            return null;
        }

        if (! preg_match('/^-?\d+$/', $value)) {
            throw new RuntimeException("{$label} integer hona chahiye.");
        }

        $number = (int) $value;

        if ($number < 0) {
            throw new RuntimeException("{$label} negative nahi ho sakta.");
        }

        return $number;
    }

    private function nullableRating(mixed $value): ?float
    {
        $rating = $this->nullableDecimal($value, 'product_rating');

        if ($rating !== null && $rating > 5) {
            throw new RuntimeException('product_rating 0 se 5 ke beech hona chahiye.');
        }

        return $rating;
    }

    private function nullableString(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function booleanValue(mixed $value, bool $default): bool
    {
        $value = strtolower(trim((string) $value));

        if ($value === '') {
            return $default;
        }

        if (in_array($value, ['1', 'true', 'yes', 'y', 'on'], true)) {
            return true;
        }

        if (in_array($value, ['0', 'false', 'no', 'n', 'off'], true)) {
            return false;
        }

        throw new RuntimeException("Boolean value 1/0, yes/no ya true/false hona chahiye.");
    }

    private function decodeLooseJson(mixed $value): mixed
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (! str_starts_with($value, '{') && ! str_starts_with($value, '[')) {
            return $value;
        }

        $decoded = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('dimensions valid JSON hona chahiye.');
        }

        return $decoded;
    }

    private function decodeJsonArray(mixed $value, string $label): ?array
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $decoded = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
            throw new RuntimeException("{$label} valid JSON array hona chahiye.");
        }

        return $decoded;
    }

    private function normalizeHeaders(array $headers): array
    {
        return array_map(function ($header) {
            $normalized = strtolower(trim((string) $header));
            $normalized = preg_replace('/[^a-z0-9]+/', '_', $normalized);
            $normalized = trim((string) $normalized, '_');

            return self::HEADER_ALIASES[$normalized] ?? $normalized;
        }, $headers);
    }

    private function mapRow(array $headers, array $row): array
    {
        $mapped = [];

        foreach ($headers as $index => $header) {
            if ($header === '') {
                continue;
            }

            $mapped[$header] = trim((string) ($row[$index] ?? ''));
        }

        return $mapped;
    }

    private function rowIsBlank(array $row): bool
    {
        foreach ($row as $cell) {
            if (trim((string) $cell) !== '') {
                return false;
            }
        }

        return true;
    }

    private function cell(array $data, string $key): string
    {
        return trim((string) ($data[$key] ?? ''));
    }

    private function jsonForCell(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        if (is_string($value)) {
            return $value;
        }

        return json_encode($value, JSON_UNESCAPED_SLASHES);
    }

    private function buildXlsx(array $rows): string
    {
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('PHP zip extension enabled nahi hai.');
        }

        $path = tempnam(sys_get_temp_dir(), 'products-xlsx-');
        $zip = new ZipArchive();

        if ($zip->open($path, ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Excel file create nahi ho payi.');
        }

        $zip->addFromString('[Content_Types].xml', $this->contentTypesXml());
        $zip->addFromString('_rels/.rels', $this->rootRelationshipsXml());
        $zip->addFromString('xl/workbook.xml', $this->workbookXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelationshipsXml());
        $zip->addFromString('xl/styles.xml', $this->stylesXml());
        $zip->addFromString('xl/worksheets/sheet1.xml', $this->worksheetXml($rows));
        $zip->close();

        $content = file_get_contents($path);
        @unlink($path);

        if ($content === false) {
            throw new RuntimeException('Excel file read nahi ho payi.');
        }

        return $content;
    }

    private function readXlsx(string $path): array
    {
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('PHP zip extension enabled nahi hai.');
        }

        $zip = new ZipArchive();

        if ($zip->open($path) !== true) {
            throw new RuntimeException('Excel file open nahi ho payi.');
        }

        $worksheetName = $this->firstWorksheetName($zip);
        $worksheetXml = $worksheetName ? $zip->getFromName($worksheetName) : false;

        if ($worksheetXml === false) {
            $zip->close();
            throw new RuntimeException('Excel sheet nahi mili.');
        }

        $sharedStrings = $this->readSharedStrings($zip);
        $zip->close();

        $sheet = simplexml_load_string($worksheetXml);

        if (! ($sheet instanceof SimpleXMLElement)) {
            throw new RuntimeException('Excel sheet XML invalid hai.');
        }

        $namespace = $this->defaultNamespace($sheet);
        $sheetChildren = $this->xmlChildren($sheet, $namespace);
        $rows = [];

        foreach ($this->xmlChildren($sheetChildren->sheetData, $namespace)->row as $row) {
            $cells = [];

            foreach ($this->xmlChildren($row, $namespace)->c as $cell) {
                $reference = (string) $cell->attributes()->r;
                $column = preg_replace('/\d+/', '', $reference);
                $index = $this->columnToIndex($column);
                $cells[$index] = $this->cellValue($cell, $namespace, $sharedStrings);
            }

            if ($cells !== []) {
                ksort($cells);
                $rows[] = $this->fillMissingCells($cells);
            }
        }

        return $rows;
    }

    private function firstWorksheetName(ZipArchive $zip): ?string
    {
        $worksheets = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);

            if (str_starts_with($name, 'xl/worksheets/') && str_ends_with($name, '.xml')) {
                $worksheets[] = $name;
            }
        }

        sort($worksheets);

        return $worksheets[0] ?? null;
    }

    private function readSharedStrings(ZipArchive $zip): array
    {
        $xml = $zip->getFromName('xl/sharedStrings.xml');

        if ($xml === false) {
            return [];
        }

        $shared = simplexml_load_string($xml);

        if (! ($shared instanceof SimpleXMLElement)) {
            return [];
        }

        $namespace = $this->defaultNamespace($shared);
        $strings = [];

        foreach ($this->xmlChildren($shared, $namespace)->si as $item) {
            $strings[] = $this->sharedStringValue($item, $namespace);
        }

        return $strings;
    }

    private function sharedStringValue(SimpleXMLElement $item, ?string $namespace): string
    {
        $children = $this->xmlChildren($item, $namespace);

        if (isset($children->t)) {
            return (string) $children->t;
        }

        $value = '';

        foreach ($children->r as $run) {
            $runChildren = $this->xmlChildren($run, $namespace);
            $value .= (string) $runChildren->t;
        }

        return $value;
    }

    private function cellValue(SimpleXMLElement $cell, ?string $namespace, array $sharedStrings): string
    {
        $type = (string) $cell->attributes()->t;
        $children = $this->xmlChildren($cell, $namespace);

        if ($type === 's') {
            return $sharedStrings[(int) $children->v] ?? '';
        }

        if ($type === 'inlineStr') {
            return $this->inlineStringValue($cell, $namespace);
        }

        if ($type === 'b') {
            return ((string) $children->v) === '1' ? '1' : '0';
        }

        return (string) ($children->v ?? '');
    }

    private function inlineStringValue(SimpleXMLElement $cell, ?string $namespace): string
    {
        $children = $this->xmlChildren($cell, $namespace);

        if (! isset($children->is)) {
            return '';
        }

        return $this->sharedStringValue($children->is, $namespace);
    }

    private function fillMissingCells(array $cells): array
    {
        $max = max(array_keys($cells));
        $row = [];

        for ($i = 0; $i <= $max; $i++) {
            $row[] = $cells[$i] ?? '';
        }

        return $row;
    }

    private function columnToIndex(string $column): int
    {
        $column = strtoupper($column);
        $index = 0;

        for ($i = 0; $i < strlen($column); $i++) {
            $index = ($index * 26) + (ord($column[$i]) - 64);
        }

        return max(0, $index - 1);
    }

    private function indexToColumn(int $index): string
    {
        $index++;
        $column = '';

        while ($index > 0) {
            $remainder = ($index - 1) % 26;
            $column = chr(65 + $remainder) . $column;
            $index = intdiv($index - 1, 26);
        }

        return $column;
    }

    private function defaultNamespace(SimpleXMLElement $xml): ?string
    {
        $namespaces = $xml->getNamespaces(true);

        return $namespaces[''] ?? null;
    }

    private function xmlChildren(SimpleXMLElement $xml, ?string $namespace): SimpleXMLElement
    {
        return $namespace ? $xml->children($namespace) : $xml->children();
    }

    private function worksheetXml(array $rows): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';
        $xml .= '<sheetData>';

        foreach ($rows as $rowIndex => $row) {
            $rowNumber = $rowIndex + 1;
            $xml .= '<row r="' . $rowNumber . '">';

            foreach ($row as $columnIndex => $value) {
                $cell = $this->indexToColumn($columnIndex) . $rowNumber;
                $xml .= '<c r="' . $cell . '" t="inlineStr"><is><t xml:space="preserve">'
                    . $this->xmlEscape($value)
                    . '</t></is></c>';
            }

            $xml .= '</row>';
        }

        $xml .= '</sheetData></worksheet>';

        return $xml;
    }

    private function xmlEscape(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }

    private function contentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>';
    }

    private function rootRelationshipsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';
    }

    private function workbookXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
            . 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="Products" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';
    }

    private function workbookRelationshipsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';
    }

    private function stylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="1"><font><sz val="11"/><name val="Calibri"/></font></fonts>'
            . '<fills count="1"><fill><patternFill patternType="none"/></fill></fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/></cellXfs>'
            . '</styleSheet>';
    }
}
