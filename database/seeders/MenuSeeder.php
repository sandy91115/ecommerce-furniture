<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $types = [
            // Footer Sitemap
            ['menu_type' => 'footer_sitemap', 'title' => 'About', 'url' => '/about', 'order' => 1],
            ['menu_type' => 'footer_sitemap', 'title' => 'Team', 'url' => '/team', 'order' => 2],
            ['menu_type' => 'footer_sitemap', 'title' => 'Portfolio', 'url' => '/portfolio-v1', 'order' => 3],
            ['menu_type' => 'footer_sitemap', 'title' => 'Clients', 'url' => '/our-clients', 'order' => 4],
            ['menu_type' => 'footer_sitemap', 'title' => 'Error', 'url' => '/error', 'order' => 5],

            // Footer Others
            ['menu_type' => 'footer_others', 'title' => 'Shipping Method', 'url' => '/shipping-method', 'order' => 1],
            ['menu_type' => 'footer_others', 'title' => 'Payment Method', 'url' => '/payment-method', 'order' => 2],
            ['menu_type' => 'footer_others', 'title' => 'My Account', 'url' => '/my-profile', 'order' => 3],
            ['menu_type' => 'footer_others', 'title' => 'Coming Soon', 'url' => '/coming-soon', 'order' => 4],

            // Footer Shop
            ['menu_type' => 'footer_shop', 'title' => 'Shop', 'url' => '/shop', 'order' => 1],
            ['menu_type' => 'footer_shop', 'title' => 'Product Single', 'url' => '/product-details', 'order' => 2],
            ['menu_type' => 'footer_shop', 'title' => 'Cart', 'url' => '/cart', 'order' => 3],
            ['menu_type' => 'footer_shop', 'title' => 'Checkout', 'url' => '/checkout', 'order' => 4],
            ['menu_type' => 'footer_shop', 'title' => 'Wishlist', 'url' => '/wishlist', 'order' => 5],

            // Footer Customer Service
            ['menu_type' => 'footer_service', 'title' => 'FAQs', 'url' => '/faq', 'order' => 1],
            ['menu_type' => 'footer_service', 'title' => 'Terms & Condition', 'url' => '/terms-and-conditions', 'order' => 2],
            ['menu_type' => 'footer_service', 'title' => 'Return Policy', 'url' => '#', 'order' => 3],
            ['menu_type' => 'footer_service', 'title' => 'Contact', 'url' => '/contact', 'order' => 4],

            // Admin Sidebar (matching current hardcoded)
            ['menu_type' => 'admin_sidebar', 'title' => 'Dashboard', 'url' => '/admin/dashboard', 'order' => 1, 'icon' => 'fas fa-tachometer-alt'],
            ['menu_type' => 'admin_sidebar', 'title' => 'Categories', 'url' => '/admin/categories', 'order' => 2, 'icon' => 'fas fa-list-alt'],
            ['menu_type' => 'admin_sidebar', 'title' => 'Products', 'url' => '/admin/products', 'order' => 3, 'icon' => 'fas fa-box'],
            ['menu_type' => 'admin_sidebar', 'title' => 'Attributes', 'url' => '/admin/attributes', 'order' => 4, 'icon' => 'fas fa-tags'],
            ['menu_type' => 'admin_sidebar', 'title' => 'Orders', 'url' => '/admin/orders', 'order' => 5, 'icon' => 'fas fa-shopping-cart'],
            ['menu_type' => 'admin_sidebar', 'title' => 'Users', 'url' => '/admin/customers', 'order' => 6, 'icon' => 'fas fa-users'],
            ['menu_type' => 'admin_sidebar', 'title' => 'Coupons', 'url' => '/admin/coupons', 'order' => 7, 'icon' => 'fas fa-ticket-alt'],
            ['menu_type' => 'admin_sidebar', 'title' => 'Blog', 'url' => '/admin/blogs', 'order' => 8, 'icon' => 'fas fa-newspaper'],
            ['menu_type' => 'admin_sidebar', 'title' => 'CMS', 'url' => '/admin/cms', 'order' => 9, 'icon' => 'fas fa-file-alt'],
            ['menu_type' => 'admin_sidebar', 'title' => 'Settings', 'url' => '/admin/settings', 'order' => 10, 'icon' => 'fas fa-cog'],
            ['menu_type' => 'admin_sidebar', 'title' => 'Menus', 'url' => '/admin/menus', 'order' => 11, 'icon' => 'fas fa-list'],
            ['menu_type' => 'admin_sidebar', 'title' => 'Enquiries', 'url' => '/admin/contacts', 'order' => 12, 'icon' => 'fas fa-envelope'],

            // Header Main (placeholder - customize later)
            ['menu_type' => 'header_main', 'title' => 'Home', 'url' => '/', 'order' => 1],
            ['menu_type' => 'header_main', 'title' => 'Shop', 'url' => '/shop', 'order' => 2],
            ['menu_type' => 'header_main', 'title' => 'Blog', 'url' => '/blog-v1', 'order' => 3],
            ['menu_type' => 'header_main', 'title' => 'Contact', 'url' => '/contact', 'order' => 4],
        ];

        foreach ($types as $menu) {
            Menu::updateOrCreate(
                ['menu_type' => $menu['menu_type'], 'title' => $menu['title'], 'url' => $menu['url']],
                $menu
            );
        }
    }
}

