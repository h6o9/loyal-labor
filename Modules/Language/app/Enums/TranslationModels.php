<?php

namespace Modules\Language\app\Enums;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

enum TranslationModels: string {
    /**
     * whenever update new case also update getAll() method
     * to return all values in array
     */
    case Blog                = "Modules\Blog\app\Models\BlogTranslation";
    case BlogCategory        = "Modules\Blog\app\Models\BlogCategoryTranslation";
    case Testimonial         = "Modules\Testimonial\app\Models\TestimonialTranslation";
    case Faq                 = "Modules\Faq\app\Models\FaqTranslation";
    case Menu                = "Modules\CustomMenu\app\Models\MenuTranslation";
    case MenuItem            = "Modules\CustomMenu\app\Models\MenuItemTranslation";
    case Tax                 = "Modules\Tax\app\Models\TaxTranslation";
    case Category            = "Modules\Product\app\Models\CategoryTranslation";
    case Brand               = "Modules\Product\app\Models\BrandTranslation";
    case Attribute           = "Modules\Product\app\Models\AttributeTranslation";
    case AttributeValue      = "Modules\Product\app\Models\AttributeValueTranslation";
    case Product             = "Modules\Product\app\Models\ProductTranslation";
    case PRODUCT_LABEL       = "Modules\Product\app\Models\ProductLabelTranslation";
    case Tag                 = "Modules\Product\app\Models\TagTranslation";
    case Coupon              = "Modules\Coupon\app\Models\CouponTranslation";
    case CustomizablePage    = "Modules\PageBuilder\app\Models\CustomizablePageTranslation";
    case SECTION_TRANSLATION = "Modules\Frontend\app\Models\SectionTranslation";

    public static function getAll(): array
    {
        return array_merge([
            self::Blog->value,
            self::BlogCategory->value,
            self::Testimonial->value,
            self::Faq->value,
            self::Menu->value,
            self::MenuItem->value,
            self::Tax->value,
            self::Category->value,
            self::Brand->value,
            self::Attribute->value,
            self::AttributeValue->value,
            self::Product->value,
            self::Tag->value,
            self::Coupon->value,
            self::CustomizablePage->value,
            self::PRODUCT_LABEL->value,
            self::SECTION_TRANSLATION->value,
        ], self::getDynamicTranslatableModels());
    }

    /**
     * @return mixed
     */
    protected static function getDynamicTranslatableModels(): array
    {
        return Cache::remember('dynamic_translatable_models', now()->addHours(1), function () {
            $dynamicModels = [];
            $modulesPath   = base_path('Modules');

            foreach (File::directories($modulesPath) as $moduleDir) {
                $configPath = $moduleDir . '/wsus.json';

                if (File::exists($configPath)) {
                    $config = json_decode(File::get($configPath), true);

                    // Check if 'translate' is true and 'translate_models' exists as an array
                    if (!empty($config['options']['translate']) && $config['options']['translate'] === true) {
                        $translateModels = $config['options']['translate_models'] ?? [];
                        if (is_array($translateModels)) {
                            foreach ($translateModels as $model) {
                                $dynamicModels[] = $model;
                            }
                        }
                    }
                }
            }

            return $dynamicModels;
        });
    }

    public static function igonreColumns(): array
    {
        return [
            'id',
            'lang_code',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }
}
