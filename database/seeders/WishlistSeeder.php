<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Modules\Product\app\Models\Product;

class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product_ids = Product::inRandomOrder()->limit(10)->pluck('id')->toArray();

        foreach ($product_ids as $product_id) {
            Wishlist::create([
                'product_id' => $product_id,
                'user_id'    => User::first()->id,
            ]);
        }

        $product_ids = Product::where('theme', 2)->inRandomOrder()->limit(10)->pluck('id')->toArray();

        foreach ($product_ids as $product_id) {
            Wishlist::create([
                'product_id' => $product_id,
                'user_id'    => User::first()->id,
            ]);
        }

    }
}
