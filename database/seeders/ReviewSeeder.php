<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::inRandomOrder()->limit(3)->get();
        if ($products->isEmpty()) return;

        $users = User::limit(5)->get();
        if ($users->isEmpty()) return;

        $reviewsData = [
            [
                'user_id' => $users->first()->id,
                'product_id' => $products[0]->id,
                'rating' => 5,
                'title' => 'Furnix',
                'comment' => "Furnixar's products have transformed my living space with their stylish designs and impeccable craftsmanship.",
            ],
            [
                'user_id' => $users[1]->id ?? $users->first()->id,
                'product_id' => $products[1]->id ?? $products[0]->id,
                'rating' => 4,
                'title' => 'Amazing Quality',
                'comment' => 'The furniture quality is outstanding, exceeded my expectations. Delivery was fast too!',
            ],
            [
                'user_id' => $users[2]->id ?? $users->first()->id,
                'product_id' => $products[0]->id,
                'rating' => 5,
                'title' => 'Perfect for Modern Home',
                'comment' => 'Love the modern design, fits perfectly in my apartment. Highly recommend!',
            ],
        ];

        foreach ($reviewsData as $data) {
            Review::updateOrCreate(
                ['user_id' => $data['user_id'], 'product_id' => $data['product_id'], 'title' => $data['title']],
                $data + ['status' => 'approved']
            );
        }
    }
}

