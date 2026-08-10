<?php

namespace Modules\Product\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Product\app\Models\ProductTos;

class TosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 8; $i++) {
            ProductTos::create([
                'question' => fake()->words(rand(3, 5), true),
                'answer'   => fake()->words(rand(10, 50), true),
            ]);
        }
    }
}
