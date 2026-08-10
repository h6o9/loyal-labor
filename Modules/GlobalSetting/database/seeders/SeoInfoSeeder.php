<?php

namespace Modules\GlobalSetting\database\seeders;

use Illuminate\Database\Seeder;
use Modules\GlobalSetting\app\Models\SeoSetting;

class SeoInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SeoSetting::truncate();

        $seoPages = [
            [
                'page_name'       => 'Home Page',
                'route'           => 'website.home',
                'seo_title'       => 'Home || WebSolutionUS',
                'seo_description' => 'Home || WebSolutionUS',
            ],
            [
                'page_name'       => 'Product Categories',
                'route'           => 'website.categories',
                'seo_title'       => 'Product Categories || WebSolutionUS',
                'seo_description' => 'Product Categories || WebSolutionUS',
            ],
            [
                'page_name'       => 'Products',
                'route'           => 'website.products',
                'seo_title'       => 'Products || WebSolutionUS',
                'seo_description' => 'Products || WebSolutionUS',
            ],
            [
                'page_name'       => 'Flash Deal Products',
                'route'           => 'website.flash.deals',
                'seo_title'       => 'Flash Deal Products || WebSolutionUS',
                'seo_description' => 'Flash Deal Products || WebSolutionUS',
            ],
            [
                'page_name'       => 'Brands',
                'route'           => 'website.brands',
                'seo_title'       => 'Brands || WebSolutionUS',
                'seo_description' => 'Brands || WebSolutionUS',
            ],
            [
                'page_name'       => 'Shops',
                'route'           => 'website.shops',
                'seo_title'       => 'Shops || WebSolutionUS',
                'seo_description' => 'Shops || WebSolutionUS',
            ],
            [
                'page_name'       => 'Gift Cards || Coupons',
                'route'           => 'website.gift.cards',
                'seo_title'       => 'Gift Cards || Coupons || WebSolutionUS',
                'seo_description' => 'Gift Cards || Coupons || WebSolutionUS',
            ],
            [
                'page_name'       => 'Frequently Asked Questions',
                'route'           => 'website.faq',
                'seo_title'       => 'Frequently Asked Questions || WebSolutionUS',
                'seo_description' => 'Frequently Asked Questions || WebSolutionUS',
            ],
            [
                'page_name'       => 'Privacy Policy Page',
                'route'           => 'website.privacy.policy',
                'seo_title'       => 'Privacy Policy || WebSolutionUS',
                'seo_description' => 'Privacy Policy || WebSolutionUS',
            ],
            [
                'page_name'       => 'Terms and conditions Page',
                'route'           => 'website.terms.and.conditions',
                'seo_title'       => 'Terms and conditions Page || WebSolutionUS',
                'seo_description' => 'Terms and conditions Page || WebSolutionUS',
            ],
            [
                'page_name'       => 'Return-policy Page',
                'route'           => 'website.return.policy',
                'seo_title'       => 'Return-policy Page || WebSolutionUS',
                'seo_description' => 'Return-policy Page || WebSolutionUS',
            ],
            [
                'page_name'       => 'Contact Page',
                'route'           => 'website.contact.us',
                'seo_title'       => 'Contact || WebSolutionUS',
                'seo_description' => 'Contact || WebSolutionUS',
            ],
            [
                'page_name'       => 'Track Orders',
                'route'           => 'website.track.order',
                'seo_title'       => 'Track Orders || WebSolutionUS',
                'seo_description' => 'Track Orders || WebSolutionUS',
            ],
            [
                'page_name'       => 'About Page',
                'route'           => 'website.about.us',
                'seo_title'       => 'About || WebSolutionUS',
                'seo_description' => 'About || WebSolutionUS',
            ],
            [
                'page_name'       => 'Blog Page',
                'route'           => 'website.blogs',
                'seo_title'       => 'Blog || WebSolutionUS',
                'seo_description' => 'Blog || WebSolutionUS',
            ],
            [
                'page_name'       => 'Join as Seller',
                'route'           => 'website.join-as-seller',
                'seo_title'       => 'Join as Seller || WebSolutionUS',
                'seo_description' => 'Join as Seller || WebSolutionUS',
            ],
            [
                'page_name'       => 'Login',
                'route'           => 'login',
                'seo_title'       => 'Login || WebSolutionUS',
                'seo_description' => 'Login || WebSolutionUS',
            ],
            [
                'page_name'       => 'Account Register',
                'route'           => 'register',
                'seo_title'       => 'Account Register || WebSolutionUS',
                'seo_description' => 'Account Register || WebSolutionUS',
            ],
            [
                'page_name'       => 'Cart',
                'route'           => 'website.cart',
                'seo_title'       => 'Cart || WebSolutionUS',
                'seo_description' => 'Cart || WebSolutionUS',
            ],
        ];

        foreach ($seoPages as $page) {
            $seoSetting                  = new SeoSetting();
            $seoSetting->page_name       = $page['page_name'];
            $seoSetting->route           = $page['route'];
            $seoSetting->seo_title       = $page['seo_title'];
            $seoSetting->seo_description = $page['seo_description'];
            $seoSetting->save();
        }
    }
}
