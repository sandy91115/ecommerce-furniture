<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Coupon;
use App\Models\Review;
use App\Models\ShippingZone;
use App\Models\ShippingRate;
use App\Models\Material;
use App\Models\Color;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $admin = User::where('email', 'admin@caromstudios.com')->first();
        if ($admin && !$admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }



        $cats = [
            ['name' => 'Living Room', 'slug' => 'living-room'],
            ['name' => 'Bedroom', 'slug' => 'bedroom'],
            ['name' => 'Dining Room', 'slug' => 'dining-room'],
            ['name' => 'Office', 'slug' => 'office'],
            ['name' => 'Outdoor', 'slug' => 'outdoor'],
        ];

        foreach ($cats as $catData) {
            Category::firstOrCreate(
                ['slug' => $catData['slug']],
                $catData
            );
        }

        $catIds = Category::pluck('id', 'slug')->toArray();

        $productsData = [
            [
                'name' => 'Wooden Sofa Set',
                'slug' => 'wooden-sofa-set',
                'sku' => 'SOFA001',
                'short_description' => 'Premium sofa set',
                'description' => 'Premium wooden sofa set.',
                'price' => 599.99,
                'sale_price' => 499.99,
                'stock' => 10,
                'status' => 'active',
                'featured' => true,
                'category_slug' => 'living-room',
            ],
            [
                'name' => 'Modern Bed Frame',
                'slug' => 'modern-bed-frame',
                'sku' => 'BED001',
                'short_description' => 'Stylish bed frame',
                'description' => 'Stylish modern bed frame.',
                'price' => 299.99,
                'stock' => 15,
                'status' => 'pending',
                'featured' => false,
                'category_slug' => 'bedroom',
            ],
        ];

        foreach ($productsData as $pData) {
            $product = Product::firstOrCreate(
                ['slug' => $pData['slug']],
                [
                    
                    'category_id' => $catIds[$pData['category_slug']],
                    'name' => $pData['name'],
                    'sku' => $pData['sku'],
                    'short_description' => $pData['short_description'],
                    'description' => $pData['description'],
                    'price' => $pData['price'],
                    'sale_price' => $pData['sale_price'] ?? null,
                    'stock' => $pData['stock'],
                    'status' => $pData['status'],
                    'featured' => $pData['featured'],
                ]
            );

            ProductImage::firstOrCreate([
                'product_id' => $product->id,
                'path' => 'products/demo.jpg',
                'featured' => true,
            ]);
        }

        // Default coupons
        Coupon::firstOrCreate(
            ['code' => 'WELCOME10'],
            [
                'type' => 'percentage',
                'value' => 10.00,
                'max_uses' => 100,
                'min_order_amount' => 100.00,
                'valid_from' => now(),
                'valid_until' => now()->addMonth(),
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'FURN50'],
            [
                'type' => 'fixed',
                'value' => 50.00,
                'max_uses' => 50,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
            ]
        );

        // Default materials
        Material::firstOrCreate(['slug' => 'wood'], ['name' => 'Wood']);
        Material::firstOrCreate(['slug' => 'metal'], ['name' => 'Metal']);
        Material::firstOrCreate(['slug' => 'fabric'], ['name' => 'Fabric']);

        // Default colors
        Color::firstOrCreate(['slug' => 'brown'], ['name' => 'Brown', 'hex_code' => '#8B4513']);
        Color::firstOrCreate(['slug' => 'black'], ['name' => 'Black', 'hex_code' => '#000000']);
        Color::firstOrCreate(['slug' => 'white'], ['name' => 'White', 'hex_code' => '#FFFFFF']);

        // Default shipping zone
        $zone = ShippingZone::firstOrCreate(
            ['name' => 'India'],
            [
                'country_codes' => ['IN'],
            ]
        );
        ShippingRate::firstOrCreate(
            ['shipping_zone_id' => $zone->id],
            [
                'name' => 'Standard Shipping',
                'type' => 'flat',
                'rate' => 50.00,
            ]
        );

        // Default review
        $firstProduct = Product::first();
        if ($firstProduct && $admin) {
            Review::firstOrCreate(
                ['product_id' => $firstProduct->id, 'user_id' => $admin->id],
                [
                    'rating' => 5,
                    'title' => 'Great Product!',
                    'comment' => 'Excellent quality furniture.',
                    'status' => 'approved',
                ]
            );
        }

$this->call([
    ReviewSeeder::class,

]);
    }

}
