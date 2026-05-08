<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MenuAliasController extends Controller
{
    private const ROUTES_BY_TITLE = [
        'dashboard' => 'admin.dashboard',
        'product' => 'admin.products.index',
        'products' => 'admin.products.index',
        'category' => 'admin.categories.index',
        'categories' => 'admin.categories.index',
        'attribute' => 'admin.attributes.index',
        'attributes' => 'admin.attributes.index',
        'order' => 'admin.orders.index',
        'orders' => 'admin.orders.index',
        'customer' => 'admin.customers.index',
        'customers' => 'admin.customers.index',
        'staff' => 'admin.staff.index',
        'coupon' => 'admin.coupons.index',
        'coupons' => 'admin.coupons.index',
        'partner' => 'admin.partners.index',
        'partners' => 'admin.partners.index',
        'blog' => 'admin.blogs.index',
        'blogs' => 'admin.blogs.index',
        'page' => 'admin.cms.index',
        'pages' => 'admin.cms.index',
        'role' => 'admin.roles.index',
        'roles-permissions' => 'admin.roles.index',
        'setting' => 'admin.settings.index',
        'settings' => 'admin.settings.index',
        'menu' => 'admin.menus.index',
        'menus' => 'admin.menus.index',
        'contact' => 'admin.contacts.index',
        'contacts' => 'admin.contacts.index',
        'quotation' => 'admin.quotations.index',
        'quotations' => 'admin.quotations.index',
        'payment-method' => 'admin.payment-methods.index',
        'payment-methods' => 'admin.payment-methods.index',
        'blog-category' => 'admin.blog-categories.index',
        'blog-categories' => 'admin.blog-categories.index',
        'trash' => 'admin.trash.index',
        'recycle-bin' => 'admin.trash.index',
        'seo' => 'admin.seo.index',
    ];

    public function __invoke(Request $request, string $adminMenuAlias)
    {
        $adminPath = trim((string) env('ADMIN_PATH', 'panel'), '/') ?: 'panel';
        $currentPath = '/' . trim($request->path(), '/');
        $legacyAdminPath = '/admin/' . trim($adminMenuAlias, '/');
        $panelPath = '/' . $adminPath . '/' . trim($adminMenuAlias, '/');

        $menu = Menu::query()
            ->active()
            ->where('menu_type', 'admin_sidebar')
            ->where(function ($query) use ($currentPath, $legacyAdminPath, $panelPath) {
                $query->where('url', $currentPath)
                    ->orWhere('url', $legacyAdminPath)
                    ->orWhere('url', $panelPath);
            })
            ->first();

        $aliasKey = Str::slug(basename(trim($adminMenuAlias, '/')));
        $routeName = self::ROUTES_BY_TITLE[Str::slug($menu?->title ?? '')]
            ?? self::ROUTES_BY_TITLE[$aliasKey]
            ?? null;

        if (! $routeName || ! route($routeName, [], false)) {
            throw new NotFoundHttpException();
        }

        $forwardedRequest = Request::create(
            route($routeName, [], false),
            'GET',
            $request->query->all(),
            $request->cookies->all(),
            [],
            $request->server->all()
        );

        if ($request->hasSession()) {
            $forwardedRequest->setLaravelSession($request->session());
        }

        $forwardedRequest->setUserResolver(fn () => $request->user());

        return app()->handle($forwardedRequest);
    }
}
