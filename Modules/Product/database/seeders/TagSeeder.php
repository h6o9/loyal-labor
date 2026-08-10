<?php

namespace Modules\Product\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Product\app\Models\Tag;
use Modules\Product\app\Models\TagTranslation;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            $tag = str(fake()->words(rand(1, 2), true));

            $productTag = Tag::create([
                'slug' => generateUniqueSlug($tag, Tag::class, 'slug', true),
            ]);

            foreach (allLanguages() as $language) {
                $tagTranslation            = new TagTranslation();
                $tagTranslation->tag_id    = $productTag->id;
                $tagTranslation->lang_code = $language->code;
                $tagTranslation->name      = $tag->title()->toString();
                $tagTranslation->save();
            }

        }
    }
}
