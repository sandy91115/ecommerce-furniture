<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Blog;
use App\Models\CmsPage;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Coupon;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;

class TrashController extends Controller
{
    public function index()
    {
        $sections = collect($this->trashables())
            ->map(function (array $config, string $type) {
                return [
                    'type' => $type,
                    'label' => $config['label'],
                    'items' => $this->queryForType($type)->latest('deleted_at')->get(),
                ];
            })
            ->values();

        $totalItems = $sections->sum(fn (array $section) => $section['items']->count());
        $canForceDelete = auth()->user()?->hasRole('super_admin') ?? false;

        return view('admin.trash.index', compact('sections', 'totalItems', 'canForceDelete'));
    }

    public function restore(string $type, int $id): RedirectResponse
    {
        $item = $this->findTrashedItem($type, $id);

        if (!$item) {
            return back()->with('error', 'Invalid recycle bin item.');
        }

        $item->restore();

        if ($type === 'vendor') {
            $item->user()->withTrashed()->first()?->restore();
        }

        return back()->with('success', $this->trashables()[$type]['singular'] . ' restored from Recycle Bin.');
    }

    public function forceDelete(string $type, int $id): RedirectResponse
    {
        if (!auth()->user()?->hasRole('super_admin')) {
            return back()->with('error', 'Only Super Admin can permanently delete items from the Recycle Bin.');
        }

        $item = $this->findTrashedItem($type, $id);

        if (!$item) {
            return back()->with('error', 'Invalid recycle bin item.');
        }

<<<<<<< HEAD
        if ($type === 'menu' && $item->is_permanent) {
            return back()->with('error', 'Permanent menu items cannot be permanently deleted.');
        }

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        if ($type === 'vendor') {
            $vendorUser = $item->user()->withTrashed()->first();
            $item->deleteFiles();
            $item->forceDelete();
            $vendorUser?->forceDelete();

            return back()->with('success', $this->trashables()[$type]['singular'] . ' permanently deleted.');
        }

        if (method_exists($item, 'deleteFiles')) {
            $item->deleteFiles();
        }

        $item->forceDelete();

        return back()->with('success', $this->trashables()[$type]['singular'] . ' permanently deleted.');
    }

    private function findTrashedItem(string $type, int $id): ?Model
    {
        if (!array_key_exists($type, $this->trashables())) {
            return null;
        }

        return $this->queryForType($type)->find($id);
    }

    private function queryForType(string $type): Builder
    {
        return match ($type) {
            'category' => Category::onlyTrashed(),
            'product' => Product::onlyTrashed(),
            'attribute' => Attribute::onlyTrashed(),
            'order' => Order::onlyTrashed(),
            'customer' => User::onlyTrashed()->where(function (Builder $query) {
                $query->whereHas('roles', function (Builder $roleQuery) {
                    $roleQuery->where('name', 'customer');
                })->orWhereDoesntHave('roles');
            }),
            'staff' => User::onlyTrashed()->whereHas('roles', function (Builder $query) {
                $query->whereIn('name', $this->staffRoleNames());
            }),
            'coupon' => Coupon::onlyTrashed(),
            'blog' => Blog::onlyTrashed(),
            'cms_page' => CmsPage::onlyTrashed(),
            'role' => Role::onlyTrashed(),
            'menu' => Menu::onlyTrashed(),
            'contact' => Contact::onlyTrashed(),
            'quotation' => Quotation::onlyTrashed(),
            'vendor' => Vendor::onlyTrashed(),
            default => throw new \InvalidArgumentException("Unsupported recycle bin type [$type]."),
        };
    }

    private function trashables(): array
    {
        return [
            'category' => ['label' => 'Categories', 'singular' => 'Category'],
            'product' => ['label' => 'Products', 'singular' => 'Product'],
            'attribute' => ['label' => 'Attributes', 'singular' => 'Attribute'],
            'order' => ['label' => 'Orders', 'singular' => 'Order'],
            'customer' => ['label' => 'Customers', 'singular' => 'Customer'],
            'staff' => ['label' => 'Staff', 'singular' => 'Staff member'],
            'coupon' => ['label' => 'Coupons', 'singular' => 'Coupon'],
            'blog' => ['label' => 'Blogs', 'singular' => 'Blog'],
            'cms_page' => ['label' => 'CMS Pages', 'singular' => 'CMS page'],
            'role' => ['label' => 'Roles & Permissions', 'singular' => 'Role'],
            'menu' => ['label' => 'Menus', 'singular' => 'Menu'],
            'contact' => ['label' => 'Contacts', 'singular' => 'Contact'],
            'quotation' => ['label' => 'Quotations', 'singular' => 'Quotation'],
            'vendor' => ['label' => 'Vendors', 'singular' => 'Vendor'],
        ];
    }

    private function staffRoleNames(): array
    {
        return ['admin', 'super_admin', 'shop_team', 'marketing_team'];
    }
}

