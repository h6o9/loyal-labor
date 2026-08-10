<?php

namespace Modules\Product\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Product\app\Models\UnitType;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'Kilogram', 'short_name' => 'kg', 'base_unit' => null, 'operator' => '*', 'operator_value' => 1],
            ['name' => 'Gram', 'short_name' => 'g', 'base_unit' => 'Kilogram', 'operator' => '/', 'operator_value' => 1000],
            ['name' => 'Liter', 'short_name' => 'l', 'base_unit' => null, 'operator' => '*', 'operator_value' => 1],
            ['name' => 'Milliliter', 'short_name' => 'ml', 'base_unit' => 'Liter', 'operator' => '/', 'operator_value' => 1000],
            ['name' => 'Piece', 'short_name' => 'pc', 'base_unit' => null, 'operator' => '*', 'operator_value' => 1],
        ];

        foreach ($units as $unit) {
            UnitType::create([
                'name'           => $unit['name'],
                'ShortName'      => $unit['short_name'],
                'base_unit'      => $unit['base_unit'] !== null ? UnitType::where('name', $unit['base_unit'])->first()->id : null,
                'operator'       => $unit['operator'],
                'operator_value' => $unit['operator_value'],
                'status'         => 1,
            ]);
        }
    }
}
