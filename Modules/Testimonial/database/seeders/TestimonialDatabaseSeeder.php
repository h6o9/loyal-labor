<?php

namespace Modules\Testimonial\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Testimonial\app\Models\Testimonial;
use Modules\Testimonial\app\Models\TestimonialTranslation;

class TestimonialDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name'    => 'Hanson Deck',
                'role'    => 'Graphic Designer',
                'image'   => 'website/images/testimonial_img_1.webp',
                'rating'  => 5,
                'comment' => 'Nauris ut ex dui. Quisque bibendum posuere ague nam puros malasada suscept urn mantis leo. Maecenas macules locus imperdiet luctus turpis porta.',
            ],
            [
                'name'    => "Ravi O Leigh",
                'role'    => 'Digital Marketer',
                'image'   => 'website/images/testimonial_img_2.webp',
                'rating'  => 5,
                'comment' => 'Nauris ut ex dui. Quisque bibendum posuere ague nam puros malasada suscept urn mantis leo. Maecenas macules locus imperdiet luctus turpis porta.',
            ],
            [
                'name'    => 'Fleece Marigold',
                'role'    => 'product design',
                'image'   => 'website/images/testimonial_img_3.webp',
                'rating'  => 5,
                'comment' => 'Nauris ut ex dui. Quisque bibendum posuere ague nam puros malasada suscept urn mantis leo. Maecenas macules locus imperdiet luctus turpis porta.',
            ],
            [
                'name'    => 'Nalcolm Function',
                'role'    => 'co founder',
                'image'   => 'website/images/testimonial_img_4.webp',
                'rating'  => 5,
                'comment' => 'Nauris ut ex dui. Quisque bibendum posuere ague nam puros malasada suscept urn mantis leo. Maecenas macules locus imperdiet luctus turpis porta.',
            ],
            [
                'name'    => "Ravi Leigh",
                'role'    => 'Digital Creator',
                'image'   => 'website/images/testimonial_img_2.webp',
                'rating'  => 5,
                'comment' => 'Nauris ut ex dui. Quisque bibendum posuere ague nam puros malasada suscept urn mantis leo. Maecenas macules locus imperdiet luctus turpis porta.',
            ],
        ];

        foreach ($testimonials as $dummy) {
            $data         = new Testimonial;
            $data->image  = $dummy['image'];
            $data->rating = $dummy['rating'];
            $data->status = true;

            if ($data->save()) {
                foreach (allLanguages() as $language) {
                    $dataTranslation                 = new TestimonialTranslation;
                    $dataTranslation->lang_code      = $language->code;
                    $dataTranslation->testimonial_id = $data->id;
                    $dataTranslation->name           = $dummy['name'];
                    $dataTranslation->designation    = $dummy['role'];
                    $dataTranslation->comment        = $dummy['comment'];
                    $dataTranslation->save();
                }
            }
        }
    }
}
