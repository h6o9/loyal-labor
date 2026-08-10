<?php

namespace Modules\Product\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Modules\Product\app\Models\Category;
use Modules\Product\app\Models\CategoryTranslation;

class CategorySeeder extends Seeder
{

    /**
     * @return mixed
     */
    public function genetareThemeBasedCategory()
    {
        return [
            1 => [
                [
                    'name'  => 'Clothing',
                    'image' => 'website/images/home_1/category/category_1.webp',
                ],
                [
                    'name'  => 'Electronics',
                    'image' => 'website/images/home_1/category/category_2.webp',
                ],
                [
                    'name'  => 'Shoes',
                    'image' => 'website/images/home_1/category/category_3.webp',
                ],
                [
                    'name'  => 'Health & Beauty',
                    'image' => 'website/images/home_1/category/category_4.webp',
                ],
                [
                    'name'  => 'Furniture',
                    'image' => 'website/images/home_1/category/category_5.webp',
                ],
                [
                    'name'  => 'Mobile',
                    'image' => 'website/images/home_1/category/category_6.webp',
                ],
                [
                    'name'  => 'Electronics',
                    'image' => 'website/images/home_1/category/category_2.webp',
                ],
                [
                    'name'  => 'Shoes',
                    'image' => 'website/images/home_1/category/category_3.webp',
                ],
                [
                    'name'  => 'Health & Beauty',
                    'image' => 'website/images/home_1/category/category_4.webp',
                ],
            ],
            2 => [
                [
                    'name'  => 'Home Appliances',
                    'image' => 'website/images/home_2/category/category_1.webp',
                ],
                [
                    'name'  => 'Man Collection',
                    'image' => 'website/images/home_2/category/category_2.webp',
                ],
                [
                    'name'  => 'Woman Collection',
                    'image' => 'website/images/home_2/category/category_3.webp',
                ],
                [
                    'name'  => 'Unisex Collection',
                    'image' => 'website/images/home_2/category/category_4.webp',
                ],
                [
                    'name'  => 'Boy Collection',
                    'image' => 'website/images/home_2/category/category_2.webp',
                ],
                [
                    'name'  => 'Room Appliances',
                    'image' => 'website/images/home_2/category/category_1.webp',
                ],
                [
                    'name'  => 'Female Collection',
                    'image' => 'website/images/home_2/category/category_4.webp',
                ],
                [
                    'name'  => 'Male Collection',
                    'image' => 'website/images/home_2/category/category_2.webp',
                ],
                [
                    'name'  => 'Girl Collection',
                    'image' => 'website/images/home_2/category/category_3.webp',
                ],
            ],
            3 => [
                [
                    'name'  => 'Pastry',
                    'image' => 'website/images/home_3/category/category_1.webp',
                ],
                [
                    'name'  => 'Bakery',
                    'image' => 'website/images/home_3/category/category_2.webp',
                ],
                [
                    'name'  => 'Drinks',
                    'image' => 'website/images/home_3/category/category_3.webp',
                ],
                [
                    'name'  => 'Vegetables',
                    'image' => 'website/images/home_3/category/category_4.webp',
                ],
                [
                    'name'  => 'Strawberries',
                    'image' => 'website/images/home_3/category/category_5.webp',
                ],
                [
                    'name'  => 'Meat',
                    'image' => 'website/images/home_3/category/category_6.webp',
                ],
                [
                    'name'  => 'Braberaze',
                    'image' => 'website/images/home_3/category/category_3.webp',
                ],
                [
                    'name'  => 'Green Leafy',
                    'image' => 'website/images/home_3/category/category_4.webp',
                ],
                [
                    'name'  => 'Fish',
                    'image' => 'website/images/home_3/category/category_6.webp',
                ],
            ],
            4 => [
                [
                    'name'  => 'Neon Sofa',
                    'image' => 'website/images/home_4/category/category_4_icon_1.webp',
                ],
                [
                    'name'  => 'Reading',
                    'image' => 'website/images/home_4/category/category_4_icon_2.webp',
                ],
                [
                    'name'  => 'Wardrobe',
                    'image' => 'website/images/home_4/category/category_4_icon_3.webp',
                ],
                [
                    'name'  => 'Sofa Set',
                    'image' => 'website/images/home_4/category/category_4_icon_4.webp',
                ],
                [
                    'name'  => 'T-Table',
                    'image' => 'website/images/home_4/category/category_4_icon_5.webp',
                ],
                [
                    'name'  => 'Sleep Bed',
                    'image' => 'website/images/home_4/category/category_4_icon_6.webp',
                ],
                [
                    'name'  => 'Writing',
                    'image' => 'website/images/home_4/category/category_4_icon_2.webp',
                ],
                [
                    'name'  => 'Home Decor',
                    'image' => 'website/images/home_4/category/category_4_icon_3.webp',
                ],
                [
                    'name'  => 'Living Room',
                    'image' => 'website/images/home_4/category/category_4_icon_4.webp',
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function getRandomCategoryName()
    {
        $themes = $this->genetareThemeBasedCategory();

        $allCategories = collect($themes)
            ->flatMap(fn($categories) => $categories)
            ->flatMap(fn($category) => is_array($category) ? [$category] : [])

            ->filter(fn($item) => is_array($item) && array_key_exists('name', $item))
            ->values();

        $random = $allCategories->random();

        return $random['name'];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker           = fake();
        $themeCategories = $this->genetareThemeBasedCategory();

        foreach ($themeCategories as $theme => $categories) {
            foreach ($categories as $parentData) {
                $name  = $parentData['name'];
                $slug  = generateUniqueSlug($name, Category::class, 'slug', true);
                $icons = $this->getIconFiles();
                $icon  = $icons[array_rand($icons)];

                $parentCategory = Category::create([
                    'slug'          => $slug,
                    'parent_id'     => null,
                    'status'        => true,
                    'image'         => $parentData['image'],
                    'icon'          => $icon,
                    'type'          => 'physical',
                    'position'      => $faker->optional()->numberBetween(1, 100),
                    'is_searchable' => 1,
                    'is_featured'   => 1,
                    'is_top'        => 1,
                    'is_popular'    => 1,
                    'is_trending'   => 1,
                    'theme'         => $theme,
                ]);

                foreach (allLanguages() as $language) {
                    CategoryTranslation::create([
                        'category_id'     => $parentCategory->id,
                        'lang_code'       => $language->code,
                        'name'            => ucfirst($name),
                        'seo_title'       => $faker->sentence,
                        'seo_description' => $faker->sentence,
                    ]);
                }

                for ($i = 0; $i <= 3; $i++) {
                    $faker     = fake();
                    $childName = $this->getRandomCategoryName();
                    $slugChild = generateUniqueSlug($childName, Category::class, 'slug', true);
                    $icon      = $icons[array_rand($icons)];

                    $childCategory = Category::create([
                        'slug'          => $slugChild,
                        'parent_id'     => $parentCategory->id,
                        'status'        => true,
                        'image'         => $parentData['image'],
                        'icon'          => $icon,
                        'type'          => 'physical',
                        'position'      => $faker->optional()->numberBetween(1, 100),
                        'is_searchable' => $faker->boolean(80),
                        'is_featured'   => 0,
                        'is_top'        => $faker->boolean(10),
                        'is_popular'    => $faker->boolean(30),
                        'is_trending'   => $faker->boolean(15),
                        'theme'         => $theme,
                    ]);

                    foreach (allLanguages() as $language) {
                        CategoryTranslation::create([
                            'category_id'     => $childCategory->id,
                            'lang_code'       => $language->code,
                            'name'            => ucfirst($childName),
                            'seo_title'       => $faker->sentence,
                            'seo_description' => $faker->sentence,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function getIconFiles()
    {
        $path = public_path('website/images/category_icons');

        $files = File::files($path);

        return collect($files)
            ->filter(function ($file) {
                return $file->getExtension() === 'webp';
            })
            ->map(function ($file) {
                return 'website/images/category_icons/' . $file->getFilename();
            })
            ->values()
            ->toArray();
    }
}
