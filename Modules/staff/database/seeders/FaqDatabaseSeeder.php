<?php

namespace Modules\Faq\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Faq\app\Models\Faq;
use Modules\Faq\app\Models\FaqTranslation;

class FaqDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dummyData = [];

        for ($i = 1; $i <= 30; $i++) {
            $dummyData[] = [
                'translations' => [
                    [
                        'lang_code' => 'en',
                        'question'  => 'Q' . $i . ': ' . Str::title(fake()->sentence(rand(4, 7))),
                        'answer'    => fake()->paragraphs(rand(1, 2), true),
                    ],
                    [
                        'lang_code' => 'ar',
                        'question'  => 'Q' . $i . ': ' . Str::title(fake()->sentence(rand(4, 7))),
                        'answer'    => fake()->paragraphs(rand(1, 2), true),
                    ],
                ],
            ];
        }

        foreach ($dummyData as $dummy) {
            $faq         = new Faq;
            $faq->status = true;
            $faq->group  = rand(1, 4);

            if ($faq->save()) {
                foreach ($dummy['translations'] as $translation) {
                    FaqTranslation::create([
                        'faq_id'    => $faq->id,
                        'lang_code' => $translation['lang_code'],
                        'question'  => $translation['question'],
                        'answer'    => $translation['answer'],
                    ]);
                }
            }
        }
    }
}
