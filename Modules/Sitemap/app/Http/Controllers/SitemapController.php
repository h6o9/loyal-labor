<?php

namespace Modules\Sitemap\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Modules\Blog\app\Models\Blog;
use Modules\Product\app\Models\Product;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('sitemap::index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $sitemap = Sitemap::create();

        $staticRoutes = [
            route('website.home'),
            route('website.categories'),
            route('website.products'),
            route('website.flash.deals'),
            route('website.brands'),
            route('website.shops'),
            route('website.gift.cards'),
            route('website.about.us'),
            route('website.contact.us'),
            route('website.faq'),
            route('website.privacy.policy'),
            route('website.return.policy'),
            route('website.terms.and.conditions'),
            route('website.join-as-seller'),
            route('website.blogs'),
            route('login'),
            route('register'),
        ];

        foreach ($staticRoutes as $url) {
            $sitemap->add(
                Url::create($url)
                    ->setLastModificationDate(now())
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        }

        foreach (customPages() as $page) {
            $sitemap->add(
                Url::create(route('website.custom.page', $page->slug))
                    ->setLastModificationDate($page?->updated_at ?? now())
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS)
            );
        }

        Product::query()->published()->get()->each(function ($product) use ($sitemap) {
            $sitemap->add(
                Url::create(route('website.product', $product->slug))
                    ->setLastModificationDate($product->updated_at ?? now())
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        Vendor::where('is_verified', 1)->where('status', 1)->get()->each(function ($shop) use ($sitemap) {
            $sitemap->add(
                Url::create(route('website.shop', $shop->shop_slug))
                    ->setLastModificationDate($shop->updated_at ?? now())
                    ->setPriority(0.6)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        Blog::where('status', 1)->get()->each(function ($blog) use ($sitemap) {
            $sitemap->add(
                Url::create(route('website.blog', $blog->slug))
                    ->setLastModificationDate($blog->updated_at ?? now())
                    ->setPriority(0.6)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        return back()->with(['message' => 'Sitemap generated!', 'alert-type' => 'success']);
    }
}
