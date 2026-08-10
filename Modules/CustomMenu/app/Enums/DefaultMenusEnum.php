<?php

namespace Modules\CustomMenu\app\Enums;

use App\Models\Vendor;
use Illuminate\Support\Collection;
use Modules\PageBuilder\app\Models\CustomizeablePage;

enum DefaultMenusEnum: string {
    case MAIN_MENU          = 'main-menu';
    case FOOTER_MENU_ONE    = 'footer-menu-1';
    case FOOTER_MENU_TWO    = 'footer-menu-2';
    case FOOTER_MENU_USER   = 'footer-menu-3';
    case FOOTER_MENU_VENDOR = 'footer-menu-4';

    public static function customPages()
    {
        $pages = CustomizeablePage::all();

        $pageObject = [];

        foreach ($pages as $page) {
            $pageObject[] = (object) [
                'name' => $page->title,
                'url'  => route('website.custom.page', $page->slug),
            ];
        }

        return collect($pageObject);
    }

    public static function allShops()
    {
        $pages = Vendor::where('is_verified', 1)->where('status', 1)->get();

        $pageObject = [];

        foreach ($pages as $page) {
            $pageObject[] = (object) [
                'name' => $page->shop_name . ' ' . __('Shop'),
                'url'  => route('website.shop', ['slug' => $page->shop_slug]),
            ];
        }

        return collect($pageObject);
    }

    public static function getAll(): Collection
    {
        return collect([
            (object) ['name' => __('Home'), 'url' => route('website.home')],
            (object) ['name' => __('Categories'), 'url' => route('website.categories')],
            (object) ['name' => __('Products'), 'url' => route('website.products')],
            (object) ['name' => __('Flash Deals'), 'url' => route('website.flash.deals')],
            (object) ['name' => __('Brands'), 'url' => route('website.brands')],
            (object) ['name' => __('Shops'), 'url' => route('website.shops')],
            (object) ['name' => __('Gift Cards'), 'url' => route('website.gift.cards')],
            (object) ['name' => __('About Us'), 'url' => route('website.about.us')],
            (object) ['name' => __('Contact Us'), 'url' => route('website.contact.us')],
            (object) ['name' => __('FAQ'), 'url' => route('website.faq')],
            (object) ['name' => __('Privacy Policy'), 'url' => route('website.privacy.policy')],
            (object) ['name' => __('Return Policy'), 'url' => route('website.return.policy')],
            (object) ['name' => __('Terms and Conditions'), 'url' => route('website.terms.and.conditions')],
            (object) ['name' => __('Blogs'), 'url' => route('website.blogs')],
            (object) ['name' => __('Join as Seller'), 'url' => route('website.join-as-seller')],
            (object) ['name' => __('Track Order'), 'url' => route('website.track.order')],
            (object) ['name' => __('Cart'), 'url' => route('website.cart')],
            (object) ['name' => __('Checkout'), 'url' => route('website.checkout')],
            (object) ['name' => __('Compare'), 'url' => route('website.compare')],
            (object) ['name' => __('Wishlist'), 'url' => route('website.wishlist')],
            (object) ['name' => __('User Dashboard'), 'url' => route('website.user.dashboard')],
            (object) ['name' => __('User Orders'), 'url' => route('website.user.orders')],
            (object) ['name' => __('Seller Dashboard'), 'url' => route('seller.dashboard')],
        ])->merge(self::customPages())->merge(self::allShops());
    }
}
