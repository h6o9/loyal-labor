<?php

namespace Modules\Shipping\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);

        $shipping_settings = [
            [
                'id'                      => 1,
                'hide_other_shipping'     => 0,
                'hide_shipping_option'    => 0,
                'sort_shipping_direction' => 'asc',
                'created_at'              => now(),
                'updated_at'              => now(),
            ],
        ];

        DB::table('shipping_settings')->insert($shipping_settings);

        $shipping_rules = [
            [
                'id'          => 1,
                'name'        => 'Out of City',
                'type'        => 'based_on_price',
                'currency_id' => null,
                'from'        => 0.00,
                'to'          => 100000000.00,
                'price'       => 10.00,
                'status'      => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'          => 2,
                'name'        => 'Inside City',
                'type'        => 'based_on_price',
                'currency_id' => null,
                'from'        => 0.00,
                'to'          => 100000000.00,
                'price'       => 4.00,
                'status'      => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        DB::table('shipping_rules')->insert($shipping_rules);
    }
}
