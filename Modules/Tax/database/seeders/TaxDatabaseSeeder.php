<?php

namespace Modules\Tax\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Tax\app\Models\Tax;
use Modules\Tax\app\Models\TaxTranslation;

class TaxDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxes = [
            'vat'           => [
                'slug'       => 'vat',
                'percentage' => 0.5,
                'status'     => true,
            ],
            'gst'           => [
                'slug'       => 'gst',
                'percentage' => 0.11,
                'status'     => true,
            ],
            'service_tax'   => [
                'slug'       => 'service_tax',
                'percentage' => 0.16,
                'status'     => true,
            ],
            'income_tax'    => [
                'slug'       => 'income_tax',
                'percentage' => 0.10,
                'status'     => true,
            ],
            'corporate_tax' => [
                'slug'       => 'corporate_tax',
                'percentage' => 0.43,
                'status'     => true,
            ],
            'sales_tax'     => [
                'slug'       => 'sales_tax',
                'percentage' => 0.3,
                'status'     => true,
            ],
        ];

        foreach ($taxes as $key => $tax) {
            $taxNew = Tax::create([
                'slug'       => $tax['slug'],
                'percentage' => $tax['percentage'],
                'status'     => $tax['status'],
            ]);

            foreach (allLanguages() as $language) {
                $newTransTax            = new TaxTranslation;
                $newTransTax->lang_code = $language->code;
                $newTransTax->tax_id    = $taxNew->id;
                $newTransTax->title     = str($key)->title();
                $newTransTax->save();
            }
        }
    }
}
