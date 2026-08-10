<?php

namespace Modules\Product\database\seeders;

use Exception;
use Illuminate\Database\Seeder;
use Modules\Product\app\Models\ProductLabel;
use Modules\Product\app\Models\ProductLabelTranslation;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $labels = [
                'New',
                'Hot',
                'Sale',
                'Top Rated',
                'Pre-Order',
                'Coming Soon',
            ];

            foreach ($labels as $label) {
                $newLabel       = new ProductLabel();
                $newLabel->slug = generateUniqueSlug($label, ProductLabel::class, 'slug', true);
                $newLabel->save();

                foreach (allLanguages() as $lang) {
                    $newTranslation                   = new ProductLabelTranslation();
                    $newTranslation->product_label_id = $newLabel->id;
                    $newTranslation->lang_code        = $lang->code;
                    $newTranslation->name             = $label;
                    $newTranslation->save();
                }
            }
        } catch (Exception $e) {
            logError('Product Label Seeder Error', $e);
        }
    }
}
