<?php

namespace Modules\Product\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Product\app\Models\Attribute;
use Modules\Product\app\Models\AttributeTranslation;
use Modules\Product\app\Models\AttributeValue;
use Modules\Product\app\Models\AttributeValueTranslation;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'id'         => 1,
                'slug'       => 'size',
                'status'     => 1,
                'created_at' => '2025-04-29 23:14:11',
                'updated_at' => '2025-04-29 23:14:11',
                'deleted_at' => null,
            ],
            [
                'id'         => 2,
                'slug'       => 'color',
                'status'     => 1,
                'created_at' => '2025-04-29 23:15:06',
                'updated_at' => '2025-04-29 23:15:06',
                'deleted_at' => null,
            ],
        ];

        Attribute::insert($attributes);

        $attributeTranslations = [
            [
                'id'           => 1,
                'attribute_id' => 1,
                'name'         => 'Size',
                'lang_code'    => 'en',
                'created_at'   => '2025-04-29 23:14:11',
                'updated_at'   => '2025-04-29 23:14:11',
            ],
            [
                'id'           => 2,
                'attribute_id' => 1,
                'name'         => 'Size',
                'lang_code'    => 'ar',
                'created_at'   => '2025-04-29 23:14:11',
                'updated_at'   => '2025-04-29 23:14:11',
            ],
            [
                'id'           => 3,
                'attribute_id' => 2,
                'name'         => 'Color',
                'lang_code'    => 'en',
                'created_at'   => '2025-04-29 23:15:06',
                'updated_at'   => '2025-04-29 23:15:06',
            ],
            [
                'id'           => 4,
                'attribute_id' => 2,
                'name'         => 'Color',
                'lang_code'    => 'ar',
                'created_at'   => '2025-04-29 23:15:06',
                'updated_at'   => '2025-04-29 23:15:06',
            ],
        ];

        AttributeTranslation::insert($attributeTranslations);

        $attributeValues = [
            [
                'id'            => 1,
                'has_thumbnail' => 0,
                'thumbnail'     => null,
                'attribute_id'  => 1,
                'order'         => 1,
                'deleted_at'    => null,
                'created_at'    => '2025-04-29 23:14:20',
                'updated_at'    => '2025-04-29 23:14:20',
            ],
            [
                'id'            => 3,
                'has_thumbnail' => 0,
                'thumbnail'     => null,
                'attribute_id'  => 1,
                'order'         => 1,
                'deleted_at'    => null,
                'created_at'    => '2025-04-29 23:14:30',
                'updated_at'    => '2025-04-29 23:14:30',
            ],
            [
                'id'            => 4,
                'has_thumbnail' => 0,
                'thumbnail'     => null,
                'attribute_id'  => 1,
                'order'         => 1,
                'deleted_at'    => null,
                'created_at'    => '2025-04-29 23:14:37',
                'updated_at'    => '2025-04-29 23:14:37',
            ],
            [
                'id'            => 5,
                'has_thumbnail' => 0,
                'thumbnail'     => null,
                'attribute_id'  => 1,
                'order'         => 1,
                'deleted_at'    => null,
                'created_at'    => '2025-04-29 23:14:43',
                'updated_at'    => '2025-04-29 23:14:43',
            ],
            [
                'id'            => 6,
                'has_thumbnail' => 0,
                'thumbnail'     => null,
                'attribute_id'  => 1,
                'order'         => 1,
                'deleted_at'    => null,
                'created_at'    => '2025-04-29 23:14:48',
                'updated_at'    => '2025-04-29 23:14:48',
            ],
            [
                'id'            => 7,
                'has_thumbnail' => 0,
                'thumbnail'     => null,
                'attribute_id'  => 1,
                'order'         => 1,
                'deleted_at'    => null,
                'created_at'    => '2025-04-29 23:14:55',
                'updated_at'    => '2025-04-29 23:14:55',
            ],
            [
                'id'            => 8,
                'has_thumbnail' => 0,
                'thumbnail'     => null,
                'attribute_id'  => 2,
                'order'         => 1,
                'deleted_at'    => null,
                'created_at'    => '2025-04-29 23:15:17',
                'updated_at'    => '2025-04-29 23:15:17',
            ],
            [
                'id'            => 9,
                'has_thumbnail' => 0,
                'thumbnail'     => null,
                'attribute_id'  => 2,
                'order'         => 1,
                'deleted_at'    => null,
                'created_at'    => '2025-04-29 23:15:23',
                'updated_at'    => '2025-04-29 23:15:23',
            ],
            [
                'id'            => 10,
                'has_thumbnail' => 0,
                'thumbnail'     => null,
                'attribute_id'  => 2,
                'order'         => 1,
                'deleted_at'    => null,
                'created_at'    => '2025-04-29 23:15:28',
                'updated_at'    => '2025-04-29 23:15:28',
            ],
            [
                'id'            => 11,
                'has_thumbnail' => 0,
                'thumbnail'     => null,
                'attribute_id'  => 2,
                'order'         => 1,
                'deleted_at'    => null,
                'created_at'    => '2025-04-29 23:15:38',
                'updated_at'    => '2025-04-29 23:15:38',
            ],
            [
                'id'            => 12,
                'has_thumbnail' => 0,
                'thumbnail'     => null,
                'attribute_id'  => 2,
                'order'         => 1,
                'deleted_at'    => null,
                'created_at'    => '2025-04-29 23:15:48',
                'updated_at'    => '2025-04-29 23:15:48',
            ],
            [
                'id'            => 13,
                'has_thumbnail' => 0,
                'thumbnail'     => null,
                'attribute_id'  => 2,
                'order'         => 1,
                'deleted_at'    => null,
                'created_at'    => '2025-04-29 23:15:53',
                'updated_at'    => '2025-04-29 23:15:53',
            ],
            [
                'id'            => 14,
                'has_thumbnail' => 0,
                'thumbnail'     => null,
                'attribute_id'  => 2,
                'order'         => 1,
                'deleted_at'    => null,
                'created_at'    => '2025-04-29 23:15:58',
                'updated_at'    => '2025-04-29 23:15:58',
            ],
            [
                'id'            => 15,
                'has_thumbnail' => 0,
                'thumbnail'     => null,
                'attribute_id'  => 2,
                'order'         => 1,
                'deleted_at'    => null,
                'created_at'    => '2025-04-29 23:16:04',
                'updated_at'    => '2025-04-29 23:16:04',
            ],
            [
                'id'            => 16,
                'has_thumbnail' => 0,
                'thumbnail'     => null,
                'attribute_id'  => 2,
                'order'         => 1,
                'deleted_at'    => null,
                'created_at'    => '2025-04-29 23:16:10',
                'updated_at'    => '2025-04-29 23:16:10',
            ],
        ];

        AttributeValue::insert($attributeValues);

        $attributeValueTranslations = [
            ['id' => 1, 'attribute_value_id' => 1, 'name' => 'S', 'lang_code' => 'en', 'created_at' => '2025-04-29 23:14:20', 'updated_at' => '2025-04-29 23:14:20'],
            ['id' => 2, 'attribute_value_id' => 1, 'name' => 'S', 'lang_code' => 'ar', 'created_at' => '2025-04-29 23:14:20', 'updated_at' => '2025-04-29 23:14:20'],
            ['id' => 5, 'attribute_value_id' => 3, 'name' => 'M', 'lang_code' => 'en', 'created_at' => '2025-04-29 23:14:30', 'updated_at' => '2025-04-29 23:14:30'],
            ['id' => 6, 'attribute_value_id' => 3, 'name' => 'M', 'lang_code' => 'ar', 'created_at' => '2025-04-29 23:14:30', 'updated_at' => '2025-04-29 23:14:30'],
            ['id' => 7, 'attribute_value_id' => 4, 'name' => 'X', 'lang_code' => 'en', 'created_at' => '2025-04-29 23:14:37', 'updated_at' => '2025-04-29 23:14:37'],
            ['id' => 8, 'attribute_value_id' => 4, 'name' => 'X', 'lang_code' => 'ar', 'created_at' => '2025-04-29 23:14:37', 'updated_at' => '2025-04-29 23:14:37'],
            ['id' => 9, 'attribute_value_id' => 5, 'name' => 'L', 'lang_code' => 'en', 'created_at' => '2025-04-29 23:14:43', 'updated_at' => '2025-04-29 23:14:43'],
            ['id' => 10, 'attribute_value_id' => 5, 'name' => 'L', 'lang_code' => 'ar', 'created_at' => '2025-04-29 23:14:43', 'updated_at' => '2025-04-29 23:14:43'],
            ['id' => 11, 'attribute_value_id' => 6, 'name' => 'XL', 'lang_code' => 'en', 'created_at' => '2025-04-29 23:14:48', 'updated_at' => '2025-04-29 23:14:48'],
            ['id' => 12, 'attribute_value_id' => 6, 'name' => 'XL', 'lang_code' => 'ar', 'created_at' => '2025-04-29 23:14:48', 'updated_at' => '2025-04-29 23:14:48'],
            ['id' => 13, 'attribute_value_id' => 7, 'name' => 'XXL', 'lang_code' => 'en', 'created_at' => '2025-04-29 23:14:55', 'updated_at' => '2025-04-29 23:14:55'],
            ['id' => 14, 'attribute_value_id' => 7, 'name' => 'XXL', 'lang_code' => 'ar', 'created_at' => '2025-04-29 23:14:55', 'updated_at' => '2025-04-29 23:14:55'],
            ['id' => 15, 'attribute_value_id' => 8, 'name' => 'Red', 'lang_code' => 'en', 'created_at' => '2025-04-29 23:15:17', 'updated_at' => '2025-04-29 23:15:17'],
            ['id' => 16, 'attribute_value_id' => 8, 'name' => 'Red', 'lang_code' => 'ar', 'created_at' => '2025-04-29 23:15:17', 'updated_at' => '2025-04-29 23:15:17'],
            ['id' => 17, 'attribute_value_id' => 9, 'name' => 'Green', 'lang_code' => 'en', 'created_at' => '2025-04-29 23:15:23', 'updated_at' => '2025-04-29 23:15:23'],
            ['id' => 18, 'attribute_value_id' => 9, 'name' => 'Green', 'lang_code' => 'ar', 'created_at' => '2025-04-29 23:15:23', 'updated_at' => '2025-04-29 23:15:23'],
            ['id' => 19, 'attribute_value_id' => 10, 'name' => 'Yellow', 'lang_code' => 'en', 'created_at' => '2025-04-29 23:15:28', 'updated_at' => '2025-04-29 23:15:28'],
            ['id' => 20, 'attribute_value_id' => 10, 'name' => 'Yellow', 'lang_code' => 'ar', 'created_at' => '2025-04-29 23:15:28', 'updated_at' => '2025-04-29 23:15:28'],
            ['id' => 21, 'attribute_value_id' => 11, 'name' => 'Violet', 'lang_code' => 'en', 'created_at' => '2025-04-29 23:15:38', 'updated_at' => '2025-04-29 23:15:38'],
            ['id' => 22, 'attribute_value_id' => 11, 'name' => 'Violet', 'lang_code' => 'ar', 'created_at' => '2025-04-29 23:15:38', 'updated_at' => '2025-04-29 23:15:38'],
            ['id' => 23, 'attribute_value_id' => 12, 'name' => 'Pink', 'lang_code' => 'en', 'created_at' => '2025-04-29 23:15:48', 'updated_at' => '2025-04-29 23:15:48'],
            ['id' => 24, 'attribute_value_id' => 12, 'name' => 'Pink', 'lang_code' => 'ar', 'created_at' => '2025-04-29 23:15:48', 'updated_at' => '2025-04-29 23:15:48'],
            ['id' => 25, 'attribute_value_id' => 13, 'name' => 'White', 'lang_code' => 'en', 'created_at' => '2025-04-29 23:15:53', 'updated_at' => '2025-04-29 23:15:53'],
            ['id' => 26, 'attribute_value_id' => 13, 'name' => 'White', 'lang_code' => 'ar', 'created_at' => '2025-04-29 23:15:53', 'updated_at' => '2025-04-29 23:15:53'],
            ['id' => 27, 'attribute_value_id' => 14, 'name' => 'Black', 'lang_code' => 'en', 'created_at' => '2025-04-29 23:15:58', 'updated_at' => '2025-04-29 23:15:58'],
            ['id' => 28, 'attribute_value_id' => 14, 'name' => 'Black', 'lang_code' => 'ar', 'created_at' => '2025-04-29 23:15:58', 'updated_at' => '2025-04-29 23:15:58'],
            ['id' => 29, 'attribute_value_id' => 15, 'name' => 'Dark Black', 'lang_code' => 'en', 'created_at' => '2025-04-29 23:16:04', 'updated_at' => '2025-04-29 23:16:04'],
            ['id' => 30, 'attribute_value_id' => 15, 'name' => 'Dark Black', 'lang_code' => 'ar', 'created_at' => '2025-04-29 23:16:04', 'updated_at' => '2025-04-29 23:16:04'],
            ['id' => 31, 'attribute_value_id' => 16, 'name' => 'Sky Blue', 'lang_code' => 'en', 'created_at' => '2025-04-29 23:16:10', 'updated_at' => '2025-04-29 23:16:10'],
            ['id' => 32, 'attribute_value_id' => 16, 'name' => 'Sky Blue', 'lang_code' => 'ar', 'created_at' => '2025-04-29 23:16:10', 'updated_at' => '2025-04-29 23:16:10'],
        ];

        AttributeValueTranslation::insert($attributeValueTranslations);
    }
}
