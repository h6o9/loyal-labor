<?php

namespace Modules\GlobalSetting\database\seeders;

use Illuminate\Database\Seeder;
use Modules\GlobalSetting\app\Models\CustomPagination;

class CustomPaginationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $item1               = new CustomPagination;
        $item1->section_name = 'Blog List';
        $item1->item_qty     = 10;
        $item1->save();

        $item2               = new CustomPagination;
        $item2->section_name = 'Blog Comment';
        $item2->item_qty     = 10;
        $item2->save();

        $item3               = new CustomPagination;
        $item3->section_name = 'Language List';
        $item3->item_qty     = 10;
        $item3->save();

        $item3               = new CustomPagination;
        $item3->section_name = 'category_list';
        $item3->item_qty     = 24;
        $item3->save();

        $item3               = new CustomPagination;
        $item3->section_name = 'brand_list';
        $item3->item_qty     = 24;
        $item3->save();

        $item3               = new CustomPagination;
        $item3->section_name = 'product_list';
        $item3->item_qty     = 18;
        $item3->save();

        $item3               = new CustomPagination;
        $item3->section_name = 'shop_list';
        $item3->item_qty     = 18;
        $item3->save();

        $item3               = new CustomPagination;
        $item3->section_name = 'shop_product_list';
        $item3->item_qty     = 9;
        $item3->save();

        $item3               = new CustomPagination;
        $item3->section_name = 'user_wishlist';
        $item3->item_qty     = 9;
        $item3->save();

        $item3               = new CustomPagination;
        $item3->section_name = 'user_reviews';
        $item3->item_qty     = 9;
        $item3->save();

        $item3               = new CustomPagination;
        $item3->section_name = 'user_orders';
        $item3->item_qty     = 10;
        $item3->save();
    }
}
