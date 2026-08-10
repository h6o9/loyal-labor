<?php

namespace Modules\Blog\database\seeders;

use App\Models\Admin;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Blog\app\Models\Blog;
use Modules\Blog\app\Models\BlogCategory;
use Modules\Blog\app\Models\BlogCategoryTranslation;
use Modules\Blog\app\Models\BlogComment;
use Modules\Blog\app\Models\BlogTranslation;

class BlogDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $dummyCategories = [
            [
                'translations' => [
                    ['lang_code' => 'en', 'title' => 'Uncategory'],
                ],
            ],
        ];
        for ($i = 0; $i < 5; $i++) {
            $title          = fake()->words(rand(1, 2), true);
            $category       = new BlogCategory;
            $category->slug = Str::slug($title);
            $category->save();

            foreach (allLanguages() as $translation) {
                $categoryTranslation                   = new BlogCategoryTranslation;
                $categoryTranslation->blog_category_id = $category->id;
                $categoryTranslation->lang_code        = $translation->code;
                $categoryTranslation->title            = str($title)->title();
                $categoryTranslation->save();
            }
        }

        $dummyBlogs = [
            [
                'image' => 'website/images/blog_img_1.webp',
                'title' => 'Tablet Vs Laptop Finding the Best Fit For Your Needs.',
                'tags'  => '[{"value":"tag one"},{"value":"tag two"}]',
            ],
            [
                'image' => 'website/images/blog_img_2.webp',
                'title' => 'Tablet Vs Laptop Finding the Best Fit For Your Needs.',
                'tags'  => '[{"value":"tag one"},{"value":"tag two"}]',
            ],
            [
                'image' => 'website/images/blog_img_3.webp',
                'title' => 'Tablet Vs Laptop Finding the Best Fit For Your Needs.',
                'tags'  => '[{"value":"tag one"},{"value":"tag two"}]',
            ],
            [
                'image' => 'website/images/blog_img_4.webp',
                'title' => 'Tablet Vs Laptop Finding the Best Fit For Your Needs.',
                'tags'  => '[{"value":"tag one"},{"value":"tag two"}]',
            ],
            [
                'image' => 'website/images/blog_img_5.webp',
                'title' => 'Tablet Vs Laptop Finding the Best Fit For Your Needs.',
                'tags'  => '[{"value":"tag one"},{"value":"tag two"}]',
            ],
            [
                'image' => 'website/images/blog_img_6.webp',
                'title' => 'Tablet Vs Laptop Finding the Best Fit For Your Needs.',
                'tags'  => '[{"value":"tag one"},{"value":"tag two"}]',
            ],
        ];

        foreach ($dummyBlogs as $value) {
            $title                  = fake()->words(rand(3, 5), true);
            $blog                   = new Blog;
            $blog->admin_id         = Admin::inRandomOrder()->first()->id ?? 1;
            $blog->blog_category_id = BlogCategory::inRandomOrder()->first()->id ?? 1;
            $blog->slug             = Str::slug($title);
            $blog->image            = $value['image'];
            $blog->views            = $faker->numberBetween(0, 400);
            $blog->show_homepage    = true;
            $blog->is_popular       = $faker->boolean;
            $blog->tags             = $value['tags'];
            $blog->status           = true;

            $blog->save();

            foreach (allLanguages() as $data) {
                $blogTranslation                  = new BlogTranslation;
                $blogTranslation->blog_id         = $blog->id;
                $blogTranslation->lang_code       = $data->code;
                $blogTranslation->title           = $title;
                $blogTranslation->description     = fake()->paragraphs(40, true);
                $blogTranslation->seo_title       = $title;
                $blogTranslation->seo_description = $faker->paragraph;
                $blogTranslation->save();
            }

            for ($j = 0; $j < 3; $j++) {
                $comment          = new BlogComment;
                $comment->user_id = User::inRandomOrder()->first()->id ?? 1;
                $comment->blog_id = $blog->id;
                $comment->name    = $faker->name;
                $comment->email   = $faker->email;
                $comment->phone   = $faker->phoneNumber;
                $comment->comment = $faker->paragraph;
                $comment->image   = 'uploads/website-images/default-avatar.png';
                $comment->status  = 1;
                $comment->save();
            }
        }

    }
}
