<?php

namespace Modules\Product\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Modules\Product\app\Models\Brand;
use Modules\Product\app\Models\BrandTranslation;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brandNames = [
            'Novexis', 'Zentura', 'Lytrix', 'Virexa', 'Omniva',
            'Quantro', 'Velora', 'Averix', 'Nexora', 'Cymetra',
            'Fynetic', 'Lumora', 'Trivexa', 'Orbitalis', 'Axiona',
            'Ecliptica', 'Bravado', 'Kinetiq', 'Solvanta', 'Zenphoria',
            'Crytix', 'Ventoro', 'Nebulon', 'Valtrix', 'Glazent',
        ];

        for ($i = 0; $i < 25; $i++) {
            $faker = fake();

            $name = str($brandNames[$i])->limit(10, '')->toString();

            $brand              = new Brand();
            $brand->slug        = generateUniqueSlug($name, Brand::class, 'slug', true);
            $brand->is_featured = $faker->boolean(20);
            $brand->icon        = $this->getBrandSvgFiles()[$i];
            $brand->image       = $this->getBrandSvgFiles()[$i];
            $brand->status      = $faker->boolean(90);
            $brand->save();

            foreach (allLanguages() as $language) {
                $brandTranslation                  = new BrandTranslation();
                $brandTranslation->brand_id        = $brand->id;
                $brandTranslation->lang_code       = $language->code;
                $brandTranslation->name            = $name;
                $brandTranslation->description     = $faker->sentence;
                $brandTranslation->seo_title       = $faker->sentence;
                $brandTranslation->seo_description = $faker->sentence;
                $brandTranslation->save();
            }
        }
    }

    /**
     * @return mixed
     */
    /**
     * @param string $base
     * @param string $modelClass
     * @param string $column
     */
    /**
     * @param string $base
     * @param string $modelClass
     * @param string $column
     */
    public function getBrandSvgFiles()
    {
        $path = public_path('website/images/brands');

        $files = File::files($path);

        return collect($files)
            ->filter(function ($file) {
                return $file->getExtension() === 'jpg';
            })
            ->map(function ($file) {
                return 'website/images/brands/' . $file->getFilename();
            })
            ->values()
            ->toArray();
    }
}
