<?php

namespace Modules\Frontend\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Frontend\app\Enums\HomepageOneEnum;
use Modules\Frontend\app\Enums\ManageThemeEnum;
use Modules\Frontend\app\Models\Home;
use Modules\Frontend\app\Models\Section;
use Modules\Frontend\app\Models\SectionTranslation;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $home_pages = [
            [
                'slug'     => ManageThemeEnum::THEME_ONE->value,
                'sections' => ManageThemeEnum::THEME_ONE->getSections(),
            ],
            [
                'slug'     => ManageThemeEnum::THEME_TWO->value,
                'sections' => ManageThemeEnum::THEME_TWO->getSections(),
            ],
            [
                'slug'     => ManageThemeEnum::THEME_THREE->value,
                'sections' => ManageThemeEnum::THEME_THREE->getSections(),
            ],
            [
                'slug'     => ManageThemeEnum::THEME_FOUR->value,
                'sections' => ManageThemeEnum::THEME_FOUR->getSections(),
            ],
        ];

        foreach ($home_pages as $home) {
            $page = Home::create(['slug' => $home['slug']]);

            foreach ($home['sections'] as $section) {
                $page_section = $page->sections()->create([
                    'name'           => $section['name'],
                    'global_content' => isset($section['global_content']) ? $section['global_content'] : null,
                ]);

                if (isset($section['translations'])) {
                    $translations = [];
                    foreach ($section['translations'] as $lang_code => $content) {
                        $translations[] = [
                            'section_id' => $page_section->id,
                            'lang_code'  => $lang_code,
                            'content'    => json_encode($content),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    SectionTranslation::insert($translations);
                }
            }
        }

        $noHomeSections = [
            HomepageOneEnum::loginSection(),
            HomepageOneEnum::registerSection(),
            HomepageOneEnum::aboutUsPage(),
            HomepageOneEnum::contactUsPage(),
        ];

        foreach ($noHomeSections as $section) {
            $page_section = Section::create([
                'name'           => $section['name'],
                'global_content' => isset($section['global_content']) ? $section['global_content'] : null,
            ]);

            if (isset($section['translations'])) {
                $translations = [];
                foreach ($section['translations'] as $lang_code => $content) {
                    $translations[] = [
                        'section_id' => $page_section->id,
                        'lang_code'  => $lang_code,
                        'content'    => json_encode($content),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                SectionTranslation::insert($translations);
            }
        }
    }

}
