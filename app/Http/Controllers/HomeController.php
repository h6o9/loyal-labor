<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Blog\app\Models\Blog;
use Modules\Blog\app\Models\BlogCategory;
use Modules\Blog\app\Models\BlogComment;
use Modules\Coupon\app\Models\Coupon;
use Modules\Faq\app\Enums\FaqGroupEnum;
use Modules\Faq\app\Models\Faq;
use Modules\Frontend\app\Enums\ManageThemeEnum;
use Modules\GlobalSetting\app\Models\SeoSetting;
use Modules\Order\app\Http\Enums\OrderStatus;
use Modules\Order\app\Models\Order;
use Modules\PageBuilder\app\Models\CustomizeablePage;
use Modules\Product\app\Models\AttributeValue;
use Modules\Product\app\Models\Brand;
use Modules\Product\app\Models\Category;
use Modules\Product\app\Models\Product;
use Modules\Testimonial\app\Models\Testimonial;

class HomeController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        $sections = setHomepageSections(isReturn: true);

        $data = match (ManageThemeEnum::tryFrom(config('services.theme'))) {
            ManageThemeEnum::THEME_ONE => $this->getHomeOneData($sections),
            ManageThemeEnum::THEME_TWO => $this->getHomeTwoData($sections),
            ManageThemeEnum::THEME_THREE => $this->getHomeThreeData($sections),
            ManageThemeEnum::THEME_FOUR => $this->getHomeFourData($sections),
            default => $this->getHomeOneData($sections),
        };

        // $data = $this->cachedHomepage();

        return view('website.index', $data);
    }

    /**
     * @return mixed
     */
    private function cachedHomepage()
    {
        $cacheKey = 'homedata' . ManageThemeEnum::tryFrom(config('services.theme'))?->value;

        return Cache::remember($cacheKey, now()->addMinutes(1), function () {
            $sections = setHomepageSections(isReturn: true);

            return match (ManageThemeEnum::tryFrom(config('services.theme'))) {
                ManageThemeEnum::THEME_ONE => $this->getHomeOneData($sections),
                ManageThemeEnum::THEME_TWO   => $this->getHomeTwoData($sections),
                ManageThemeEnum::THEME_THREE => $this->getHomeThreeData($sections),
                ManageThemeEnum::THEME_FOUR  => $this->getHomeFourData($sections),
                default                      => $this->getHomeOneData($sections),
            };
        });
    }

    /**
     * @param $sections
     */
    private function getHomeOneData($sections): array
    {
        $today = now()->startOfDay();

        $limits = [
            'brand'       => $sections->brand_section->limit ?? 10,
            'bestSelling' => $sections->best_selling_section?->limit ?? 10,
            'featured'    => $sections->feature_products_section?->limit ?? 10,
            'bundleCombo' => $sections->bundle_combo_section?->limit ?? 10,
            'testimonial' => $sections->testimonials_section?->limit ?? 10,
            'blog'        => $sections->blog_section?->limit ?? 10,
        ];

        $productQuery = fn() => Product::with(['labels.translation'])->published();

        return [
            'sections'            => $sections,

            'brands'              => Brand::where('status', 1)
                ->limit($limits['brand'])
                ->get(),

            'bestSellingProducts' => $productQuery()
                ->withCount('orders')
                ->orderByDesc('orders_count')
                ->limit($limits['bestSelling'])
                ->get(),

            'featuredProducts'    => $productQuery()
                ->where('is_featured', 1)
                ->limit($limits['featured'])
                ->get(),

            'flashDeals'          => $productQuery()
                ->where('is_flash_deal', 1)
                ->whereDate('flash_deal_start', '<=', $today)
                ->whereDate('flash_deal_end', '>=', $today)
                ->get(),

            'flashDealsProduct'   => $productQuery()
                ->where('is_flash_deal', 1)
                ->whereDate('flash_deal_start', '<=', $today)
                ->whereDate('flash_deal_end', '>=', $today)
                ->inRandomOrder()
                ->first(),

            'bundleComboProducts' => $productQuery()
                ->where($this->themeOneComboType($sections->bundle_combo_section), 1)
                ->limit($limits['bundleCombo'])
                ->get(),

            'testimonials'        => Testimonial::with('translation')
                ->where('status', 1)
                ->limit($limits['testimonial'])
                ->get(),

            'blogs'               => Blog::with('translation')
                ->where('status', 1)
                ->limit($limits['blog'])
                ->get(),
        ];
    }

    /**
     * @param $section
     */
    private function themeOneComboType($section)
    {
        $bundleComboType = $section->collection_type ?? 'best_seller';

        return match ($bundleComboType) {
            'best_seller' => 'is_best_selling',
            'popular' => 'is_popular',
            'featured' => 'is_featured',
            default => 'is_best_selling',
        };
    }

    /**
     * @param $sections
     */
    private function getHomeTwoData($sections): array
    {
        $data['sections'] = $sections;

        $today = Carbon::now()->startOfDay();

        $flashDealLimit = $data['sections']?->flash_deal_section?->limit ?? 10;

        $data['flashDeals'] = Product::with('labels')
            ->where('is_flash_deal', 1)
            ->whereDate('flash_deal_start', '<=', $today)
            ->whereDate('flash_deal_end', '>=', $today)
            ->published()
            ->limit($flashDealLimit)
            ->get();

        $favoriteProductsLimit = $data['sections']?->favorite_products_section?->limit ?? 10;

        $data['favoriteProductCategories'] = Category::query()
            ->with([
                'products' => function ($query) use ($favoriteProductsLimit) {
                    $query->published()
                        ->with('labels')
                        ->orderByDesc(
                            DB::table('wishlists')
                                ->selectRaw('COUNT(*)')
                                ->whereColumn('wishlists.product_id', 'products.id')
                        )
                        ->take($favoriteProductsLimit);
                },
            ])
            ->withCount([
                'products as wishlist_total' => function ($query) {
                    $query->join('wishlists', 'wishlists.product_id', '=', 'products.id');
                },
            ])
            ->orderByDesc('wishlist_total')
            ->take(4)
            ->get();

        $featuredLimit = $data['sections']?->feature_products_section?->limit ?? 10;

        $data['featureProducts'] = Product::with('labels')->where('is_featured', 1)->published()->limit($featuredLimit)->get();

        $hotDealLimit = $data['sections']?->hot_deal_section?->limit ?? 3;

        $hotDealType = $data['sections']?->hot_deal_section?->collection_type ?? 'best_seller';

        $hotDealType = match ($hotDealType) {
            'best_seller' => 'is_best_selling',
            'popular' => 'is_popular',
            'featured' => 'is_featured',
            default => 'is_best_selling',
        };

        $data['hotDealProducts'] = Product::with('labels')->when(
            $hotDealType,
            function ($q) use ($hotDealType) {
                $q->where($hotDealType, 1);
            }
        )->published()->limit($hotDealLimit)->get();

        $blogLimit = $data['sections']?->blog_section?->limit ?? 10;

        $data['blogs'] = Blog::with('translation')
            ->where('status', 1)
            ->limit($blogLimit)
            ->get();

        return $data;
    }

    /**
     * @param $sections
     */
    private function getHomeThreeData($sections): array
    {
        $data['sections'] = $sections;

        $featureProductLimit = $sections?->feature_products_section?->limit ?? 10;

        $data['featureProductCategories'] = Category::query()
            ->withCount([
                'products as featured_total' => function ($query) {
                    $query->where('is_featured', 1);
                },
            ])
            // ->having('featured_total', '>=', 4)
            ->orderByDesc('featured_total')
            ->with([
                'products' => function ($query) use ($featureProductLimit) {
                    $query
                        ->with(['labels'])
                        ->withCount('reviews')
                        ->withAvg('reviews', 'rating')
                        ->where('is_featured', 1)
                        ->published()
                        ->limit($featureProductLimit);
                },
            ])
            ->take(5)
            ->get();

        $flashDealLimit = $sections?->flash_deal_section?->limit ?? 10;

        $today = now();

        $data['flashDealsProducts'] = Product::with('labels')
            ->withCount([
                'orderDetails' => function ($query) {
                    $query->whereHas('order', function ($q) {
                        $q->where(['is_flash_deal' => 1, 'order_status' => OrderStatus::DELIVERED->value]);
                    });
                },
            ])
            ->where('is_flash_deal', 1)
            ->whereDate('flash_deal_start', '<=', $today)
            ->whereDate('flash_deal_end', '>=', $today)
            ->published()
            ->latest()
            ->limit($flashDealLimit)
            ->get();

        $topProductsSectionLimit = $data['sections']?->top_products_section?->limit ?? 10;

        $data['topProductsSectionOne'] = Product::with(['labels'])
            ->published()
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_count')
            ->orderByDesc('reviews_avg_rating')
            ->limit($topProductsSectionLimit)
            ->get();

        $data['topProductsSectionTwo'] = Product::with(['labels'])
            ->published()
            ->withCount(['orderDetails' => function ($q) {
                $q->whereRelation('order', 'order_status', OrderStatus::DELIVERED->value);
            }])
            ->orderByDesc('order_details_count')
            ->limit($topProductsSectionLimit)
            ->get();

        $popularProductsSectionLimit = $data['sections']?->popular_products_section?->limit ?? 10;

        $data['popularProducts'] = Product::with(['labels'])
            ->published()
            ->where('is_popular', 1)
            ->limit($popularProductsSectionLimit)
            ->get();

        $blogLimit = $data['sections']?->blog_section?->limit ?? 10;

        $data['blogs'] = Blog::with('translation')
            ->where('status', 1)
            ->limit($blogLimit)
            ->get();

        return $data;
    }

    /**
     * @param $sections
     */
    private function getHomeFourData($sections): array
    {
        $data['sections'] = $sections;

        $flashDealLimit = $sections->flash_deal_section->limit ?? 10;

        $today = Carbon::now()->startOfDay();

        $data['flashDeals'] = Product::with('labels')
            ->where('is_flash_deal', 1)
            ->whereDate('flash_deal_start', '<=', $today)
            ->whereDate('flash_deal_end', '>=', $today)
            ->published()
            ->limit($flashDealLimit)
            ->get();

        $bestSalesLimit = $sections->best_sales_section->limit ?? 10;

        $latestProducts = Product::query()
            ->published()
            ->latest()
            ->limit($bestSalesLimit)
            ->get();

        $mostSalesProducts = Product::with('labels.translation')->withCount('orders')
            ->published()
            ->orderByDesc('orders_count');

        $topRatedProducts = Product::with('labels.translation')
            ->published()
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_count')
            ->orderByDesc('reviews_avg_rating')
            ->limit($bestSalesLimit)
            ->get();

        $data['bestSellingProducts'] = [
            'latestProducts'    => $latestProducts,
            'mostSalesProducts' => $mostSalesProducts->limit($bestSalesLimit)->get(),
            'topRatedProducts'  => $topRatedProducts,
        ];

        $data['topSellingProducts'] = $mostSalesProducts->limit(4)->get();

        $productSku = $sections->filtered_product_section->product_sku ?? $mostSalesProducts->first()->sku ?? null;

        $data['filteredProduct'] = $productSku ? Product::whereSku($productSku)->first() : null;

        $blogLimit = $data['sections']?->blog_section?->limit ?? 10;

        $data['blogs'] = Blog::with('translation')
            ->where('status', 1)
            ->limit($blogLimit)
            ->get();

        return $data;
    }

    /**
     * @param Request $request
     */
    public function categories(Request $request)
    {
        $perPage = customPagination()->category_list ?? 24;

        $categories = Category::with([
            'translation',
        ])
            ->withCount('products')
            ->latest()
            ->paginate($perPage);

        return view('website.categories', compact('categories'));
    }

    public function brands()
    {
        $perPage = customPagination()->brand_list ?? 24;

        $brands = Brand::with([
            'translation',
        ])
            ->withCount('products')
            ->active()
            ->latest()
            ->paginate($perPage);

        return view('website.brands', compact('brands'));
    }

    /**
     * @param Request $request
     */
    public function products(Request $request)
    {
        $colorNames = request()->input('colors', []);

        $query = Product::query()->published();

        $query = $query->with([
            'variants',
            'variantImage',
            'categories',
            'translation',
            'variants.options',
            'variants.options.attribute',
            'variants.options.attributeValue',
            'variants.options.attributeValue.translation',
        ]);

        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->whereHas('translations', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%');
            });
        });

        $query->when($request->filled('category'), function ($q) use ($request) {
            $q->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        });

        $query->when($request->filled('categories'), function ($q) use ($request) {
            $q->whereHas('categories', function ($q) use ($request) {
                if (!is_array($request->categories)) {
                    $request->categories = [$request->categories];
                }
                $q->whereIn('slug', $request->categories);
            });
        });

        $query->when($colorNames, function ($q) use ($colorNames) {
            $q->whereHas('variants.optionValues.translations', function ($q) use ($colorNames) {
                $q->whereIn('name', $colorNames);
            });
        });

        $query->when($request->filled('brand'), function ($q) use ($request) {
            $q->whereHas('brand', function ($q) use ($request) {
                if (!is_array($request->brand)) {
                    $request->brand = [$request->brand];
                }
                $q->whereIn('slug', $request->brand);
            });
        });

        $query->when($request->filled('sortby'), function ($q) use ($request) {
            $request->sortby == 'latest' ? $q->latest() : $q->oldest();
        }, function ($q) {
            $q->latest();
        });

        $query->when($request->filled('from') && $request->filled('to'), function ($q) use ($request) {
            $from = revertToUSD($request->from, false);
            $to   = revertToUSD($request->to, false);

            $q->where(function ($q) use ($from, $to) {
                $q->where(function ($q) use ($from, $to) {
                    $q->whereNull('offer_price')
                        ->where('price', '>=', $from)
                        ->where('price', '<=', $to);
                })->orWhere(function ($q) use ($from, $to) {
                    $q->whereNotNull('offer_price')
                        ->where('offer_price', '>=', $from)
                        ->where('offer_price', '<=', $to);
                });
            });
        })->when($request->filled('from') && !$request->filled('to'), function ($q) use ($request) {
            $from = revertToUSD($request->from, false);

            $q->where(function ($q) use ($from) {
                $q->where(function ($q) use ($from) {
                    $q->whereNull('offer_price')
                        ->where('price', '>=', $from);
                })->orWhere(function ($q) use ($from) {
                    $q->whereNotNull('offer_price')
                        ->where('offer_price', '>=', $from);
                });
            });
        })->when(!$request->filled('from') && $request->filled('to'), function ($q) use ($request) {
            $to = revertToUSD($request->to, false);

            $q->where(function ($q) use ($to) {
                $q->where(function ($q) use ($to) {
                    $q->whereNull('offer_price')
                        ->where('price', '<=', $to);
                })->orWhere(function ($q) use ($to) {
                    $q->whereNotNull('offer_price')
                        ->where('offer_price', '<=', $to);
                });
            });
        });

        $perPage = customPagination()->product_list ?? 18;

        $perPage = $request->get('per-page', $perPage);

        $data['products'] = $query->paginate($perPage)->withQueryString();

        $data['categories'] = Category::whereNull('parent_id')
            ->with(['children' => function ($q) {
                $q->withCount([
                    'products' => function ($q) {
                        $q->published();
                    },
                ]);
            }])
            ->withCount([
                'products' => function ($q) {
                    $q->published();
                },
            ])
            ->whereStatus(1)
            ->latest()
            ->get()
            ->map(function ($category) {
                $children = $category->children ?? collect();

                $category->children = $children->filter(function ($child) {
                    return ($child->products_count ?? 0) > 0;
                })->values();

                $childrenTotal                  = $category->children->sum('products_count');
                $category->total_products_count = ($category->products_count ?? 0) + $childrenTotal;

                return $category;
            })
            ->filter(function ($category) {
                return ($category->total_products_count ?? 0) > 0;
            })
            ->values();

        $data['brands'] = Brand::with('translation')->whereStatus(1)->latest()->get();

        $data['minPrice'] = 0;

        $data['maxPrice'] = $query->max('price');

        $data['colors'] = AttributeValue::with('translation')->whereHas('attribute', function ($q) {
            $q->where('slug', 'color');
        })->get();

        return view('website.products', $data);
    }

    /**
     * @return mixed
     */
    public function product($slug)
    {
        $product = Product::with([
            'variants',
            'variantImage' => [
                'attribute',
                'attributeValue',
            ],
            'categories'   => function ($q) {
                $q->with('translation');
            },
            'brand'        => function ($q) {
                $q->with('translation');
            },
            'translation',
            'vendor'       => function ($q) {
                $q->withCount('reviews')->withAvg('reviews', 'rating');
            },
            'variants'     => [
                'options' => [
                    'attribute',
                    'attributeValue.translation',
                ],
            ],
            'reviews'      => function ($q) {
                $q->where('status', 1)
                    ->with('user')
                    ->latest()
                    ->take(10);
            },
        ])
            ->where('slug', $slug)->published()->firstOrFail();

        $product->increment('viewed');

        $relatedProducts = Product::with(['labels', 'categories'])
            ->where('id', '!=', $product->id)
            ->where(function ($query) use ($product) {
                $query->where('brand_id', $product->brand_id)
                    ->orWhere('vendor_id', $product->vendor_id)
                    ->orWhereHas('labels', function ($q) use ($product) {
                        $q->whereIn('product_labels.id', $product->labels->pluck('id'));
                    })
                    ->orWhereHas('categories', function ($q) use ($product) {
                        $q->whereIn('categories.id', $product->categories->pluck('id'));
                    });
            })
            ->published()
            ->latest()
            ->limit(4)
            ->get();

        $sections = getSection('about_us_page', false);

        pushToGTM([
            'event'        => 'page_view',
            'page_type'    => 'product_detail',
            'product_id'   => $product->id,
            'product_name' => $product->name,
            'user_id'      => auth()->id() ?? 0,
            'user_role'    => auth()->check() ? auth()->user()->name : 'guest',
            'language'     => getSessionLanguage(),
        ]);

        $pixelData = [
            'content_ids'  => [$product->id],
            'content_name' => $product->name,
            'content_type' => 'product',
            'value'        => (float) $product->price,
            'currency'     => 'USD',
        ];

        session()->flash('pixel_payload', [
            'event' => 'ViewContent',
            'data'  => $pixelData,
        ]);

        return view('website.product', compact('product', 'sections', 'relatedProducts'));
    }

    /**
     * @param Request $request
     */
    public function productModal(Request $request)
    {
        $id = $request->id;

        $product = Product::query()->published()->with([
            'variants' => [
                'options' => [
                    'attribute',
                    'attributeValue.translation',
                ],
                'manageStocks',
            ],
            'translation',
            'labels'   => ['translation'],
        ])->findOrFail($id);

        $gtmData = [
            'event'        => 'page_view',
            'page_type'    => 'product_detail',
            'product_id'   => $product->id,
            'product_name' => $product->name,
            'user_id'      => auth()->id() ?? 0,
            'user_role'    => auth()->check() ? auth()->user()->name : 'guest',
            'language'     => getSessionLanguage(),
        ];

        $pixelData = [
            'event' => 'ViewContent',
            'data'  => [
                'content_ids'  => [$product->id],
                'content_name' => $product->name,
                'content_type' => 'product',
                'value'        => (float) $product->price,
                'currency'     => 'USD',
            ],
        ];

        return response()->json([
            'status'     => 'success',
            'body'       => view('components::product-model-body', compact('product'))->render(),
            'attributes' => $product->selectable_variants ?? [],
            'gtm'        => $gtmData,
            'pixel'      => $pixelData,
        ]);
    }

    public function flashDeals()
    {
        $today = Carbon::now()->startOfDay();

        $products = Product::with('labels')
            ->where('is_flash_deal', 1)
            ->whereDate('flash_deal_start', '<=', $today)
            ->whereDate('flash_deal_end', '>=', $today)
            ->published()->paginate();

        return view('website.flash-deals', compact('products'));
    }

    /**
     * @return mixed
     */
    public function aboutUs()
    {
        $data['sections'] = getSection('about_us_page', false);

        if (!$data['sections']?->about_us_page?->status) {
            abort(404);
        }

        $testimonialLimit = $data['sections']?->about_us_page?->testimonial_limit ?? 10;

        $data['testimonials'] = Testimonial::with('translation')->where('status', 1)->limit($testimonialLimit)->get();

        $blogLimit = $data['sections']?->about_us_page?->blog_limit ?? 3;

        $data['blogs'] = Blog::with('translation')
            ->where('status', 1)
            ->limit($blogLimit)
            ->get();

        return view('website.about-us', $data);
    }

    /**
     * @return mixed
     */
    public function contactUs()
    {
        $data['sections'] = getSection('contact_us_page', false);

        if (!$data['sections']?->contact_us_page?->status) {
            abort(404);
        }

        return view('website.contact-us', $data);
    }

    public function faq()
    {
        $faqs = Faq::with('translation')->where('status', 1)->get();

        $enum = FaqGroupEnum::class;

        return view('website.faq', compact('faqs', 'enum'));
    }

    /**
     * @return mixed
     */
    public function giftCards()
    {
        $coupons = Coupon::select('id', 'coupon_code', 'discount', 'usage_limit_per_coupon', 'free_shipping', 'can_use_with_campaign', 'is_percent', 'start_date', 'expired_date', 'is_never_expired', 'show_homepage', 'status')->where([
            'show_homepage' => 1,
            'status'        => 1,
        ])->with('translation')->where(function ($query) {
            $query->where('is_never_expired', 1)
                ->orWhere('expired_date', '>=', Carbon::now());
        })->get();

        return view('website.gift-cards', compact('coupons'));
    }

    /**
     * @param $orderId
     * @param null       $email
     */
    public function trackOrder($orderId = null, $email = null)
    {
        $order   = null;
        $orderId = request('orderId', $orderId);
        $email   = request('email', $email);

        if ($orderId) {
            if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $order = Order::with([
                    'shippingAddress',
                    'billingAddress',
                    'items',
                    'transactionHistories',
                    'orderStatusHistory',
                    'paymentStatusHistory',
                ])
                    ->where('order_id', $orderId)
                    ->where(function ($query) use ($email) {
                        $query->whereRelation('shippingAddress', 'email', $email)
                            ->orWhereRelation('billingAddress', 'email', $email);
                    })
                    ->firstOr(function () {
                        throw new HttpResponseException(redirect()->back()->with([
                            'message'    => __('Order not found'),
                            'alert-type' => 'error',
                        ]));
                    });
            } else {
                return to_route('website.track.order')->with([
                    'message'    => __('Email is required to track the order.'),
                    'alert-type' => 'error',
                ]);
            }
        } elseif (!$orderId && $email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return to_route('website.track.order')->with([
                'message'    => __('Order ID is required to track the order.'),
                'alert-type' => 'error',
            ]);
        }

        return view('website.track-order', compact('order'));
    }

    /**
     * @return mixed
     */
    public function blogs()
    {
        $blogs = Blog::with('translation', 'category.translation')->orderBy('id', 'desc')
            ->active()
            ->when(request()->filled('search'), function ($query) {
                $query->with('translations')->whereHas('translations', function ($q) {
                    $q->where('title', 'like', '%' . request()->get('search') . '%');
                });
            })
            ->when(request()->filled('category'), function ($query) {
                $query->whereHas('category.translation', function ($q) {
                    $q->where('slug', request()->get('category'));
                });
            })
            ->when(request()->filled('tag'), function ($query) {
                $tag = request()->get('tag');
                $query->where('tags', 'like', '%"value":"' . $tag . '"%');
            });

        $perPage     = customPagination()->blog_list ?? 9;
        $blogs       = $blogs->paginate($perPage);
        $seo_setting = SeoSetting::where('page_name', 'Blog Page');

        return view('website.blog.blogs', compact('blogs', 'seo_setting'));
    }

    /**
     * @param $id
     */
    public function blog($slug)
    {
        $blog = Blog::with('translation', 'category.translation', 'comments')->withCount('comments')->where('slug', $slug)->active()->firstOr(function () {
            throw new HttpResponseException(redirect()->back()->with(['message' => __('Blog not found'), 'alert-type' => 'error']));
        });

        $perPage = customPagination()->blog_comment ?? 12;

        $comments = $blog->comments()->withNested()->active()->latest()->paginate($perPage);

        $recentPosts = Blog::with('translation')->orderBy('id', 'desc')->where(['status' => 1])->take(4)->get();

        $categories = BlogCategory::with('translation')->withCount('posts')->where('status', 1)->get();

        $allTags = Blog::pluck('tags')
            ->flatMap(function ($item) {
                $decoded = json_decode($item, true);
                return collect($decoded)->pluck('value');
            })
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(10);

        return view('website.blog.blog', compact('blog', 'comments', 'recentPosts', 'categories', 'allTags'));
    }

    /**
     * @param Request $request
     */
    public function blogCommentStore(Request $request, $slug)
    {
        $request->validate([
            'comment' => 'required|string|max:5000',
        ]);

        $blog = Blog::where('slug', $slug)->active()->firstOr(function () {
            throw new HttpResponseException(redirect()->back()->with(['message' => __('Blog not found'), 'alert-type' => 'error']));
        });

        $status = getSettingStatus('comments_auto_approved') ?? 0;

        BlogComment::create([
            'name'    => auth()->guard('web')->user()->name,
            'email'   => auth()->guard('web')->user()->email,
            'phone'   => auth()->guard('web')->user()->phone ?? null,
            'image'   => auth()->guard('web')->user()->image ?? getSettings('default_user_image'),
            'blog_id' => $blog->id,
            'user_id' => auth()->guard('web')->user()->id,
            'comment' => $request->comment,
            'status'  => $status,
        ]);

        return back()->with(['message' => __('Comment added successfully, wait for approval'), 'alert-type' => 'success']);
    }

    /**
     * @param Request $request
     */
    public function compare(Request $request)
    {
        return view('website.compare');
    }

    /**
     * @return mixed
     */
    public function shops()
    {
        $perPage = customPagination()->shop_list ?? 24;

        $shops = Vendor::where('is_verified', 1)
            ->whereNull('verification_token')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->paginate($perPage);

        return view('website.shops', compact('shops'));
    }

    /**
     * @param $slug
     */
    public function shop(Request $request, $slug)
    {
        $data['shop'] = Vendor::where('is_verified', 1)
            ->whereNull('verification_token')
            ->where('is_verified', 1)
            ->where('shop_slug', $slug)
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->firstOrFail();

        $perPage = customPagination()->shop_product_list ?? 9;

        $colorNames = request()->input('colors', []);

        $query = Product::where('vendor_id', $data['shop']->id)->withCount('orders')->published();

        $query = $query->with([
            'orders',
            'variants',
            'variantImage',
            'categories',
            'translation',
            'variants.options',
            'variants.options.attribute',
            'variants.options.attributeValue',
            'variants.options.attributeValue.translation',
        ]);

        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->whereHas('translations', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%');
            });
        });

        $query->when($request->filled('category'), function ($q) use ($request) {
            $q->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        });

        $query->when($request->filled('categories'), function ($q) use ($request) {
            $q->whereHas('categories', function ($q) use ($request) {
                if (!is_array($request->categories)) {
                    $request->categories = [$request->categories];
                }
                $q->whereIn('slug', $request->categories);
            });
        });

        $query->when($colorNames, function ($q) use ($colorNames) {
            $q->whereHas('variants.optionValues.translations', function ($q) use ($colorNames) {
                $q->whereIn('name', $colorNames);
            });
        });

        $query->when($request->filled('brand'), function ($q) use ($request) {
            $q->whereHas('brand', function ($q) use ($request) {
                if (!is_array($request->brand)) {
                    $request->brand = [$request->brand];
                }
                $q->whereIn('slug', $request->brand);
            });
        });

        $query->when($request->filled('sortby'), function ($q) use ($request) {
            $request->sortby == 'latest' ? $q->latest() : $q->oldest();
        }, function ($q) {
            $q->latest();
        });

        $query->when($request->filled('from') && $request->filled('to'), function ($q) use ($request) {
            $from = revertToUSD($request->from, false);
            $to   = revertToUSD($request->to, false);

            $q->where(function ($q) use ($from, $to) {
                $q->where(function ($q) use ($from, $to) {
                    $q->whereNull('offer_price')
                        ->where('price', '>=', $from)
                        ->where('price', '<=', $to);
                })->orWhere(function ($q) use ($from, $to) {
                    $q->whereNotNull('offer_price')
                        ->where('offer_price', '>=', $from)
                        ->where('offer_price', '<=', $to);
                });
            });
        })->when($request->filled('from') && !$request->filled('to'), function ($q) use ($request) {
            $from = revertToUSD($request->from, false);

            $q->where(function ($q) use ($from) {
                $q->where(function ($q) use ($from) {
                    $q->whereNull('offer_price')
                        ->where('price', '>=', $from);
                })->orWhere(function ($q) use ($from) {
                    $q->whereNotNull('offer_price')
                        ->where('offer_price', '>=', $from);
                });
            });
        })->when(!$request->filled('from') && $request->filled('to'), function ($q) use ($request) {
            $to = revertToUSD($request->to, false);

            $q->where(function ($q) use ($to) {
                $q->where(function ($q) use ($to) {
                    $q->whereNull('offer_price')
                        ->where('price', '<=', $to);
                })->orWhere(function ($q) use ($to) {
                    $q->whereNotNull('offer_price')
                        ->where('offer_price', '<=', $to);
                });
            });
        });

        $perPage = $request->get('per-page', $perPage);

        $data['products'] = $query->paginate($perPage)->withQueryString();

        $data['categories'] = Category::whereNull('parent_id')
            ->with(['children' => function ($q) use ($data) {
                $q->withCount([
                    'products' => function ($q) use ($data) {
                        $q->where('vendor_id', $data['shop']->id)->published();
                    },
                ]);
            }])
            ->withCount([
                'products' => function ($q) use ($data) {
                    $q->where('vendor_id', $data['shop']->id)->published();
                },
            ])
            ->whereStatus(1)
            ->latest()
            ->get()
            ->map(function ($category) {
                $children = $category->children ?? collect();

                $category->children = $children->filter(function ($child) {
                    return ($child->products_count ?? 0) > 0;
                })->values();

                $childrenTotal                  = $category->children->sum('products_count');
                $category->total_products_count = ($category->products_count ?? 0) + $childrenTotal;

                return $category;
            })
            ->filter(function ($category) {
                return ($category->total_products_count ?? 0) > 0;
            })
            ->values();

        $data['brands'] = Brand::with('translation')->whereStatus(1)->latest()->get();

        $data['minPrice'] = 0;

        $data['maxPrice'] = $query->max('price');

        $data['colors'] = AttributeValue::with('translation')->whereHas('attribute', function ($q) {
            $q->where('slug', 'color');
        })->get();

        return view('website.shop', $data);
    }

    public function privacyPolicy()
    {
        $data = CustomizeablePage::with('translation')->where('slug', 'privacy-policy')->first();

        return view('website.custom-page', compact('data'));
    }

    public function returnPolicy()
    {
        $data = CustomizeablePage::with('translation')->where('slug', 'return-policy')->first();

        return view('website.custom-page', compact('data'));
    }

    public function termsAndConditions()
    {
        $data = CustomizeablePage::with('translation')->where('slug', 'terms-contidions')->first();

        return view('website.custom-page', compact('data'));
    }

    /**
     * @param $slug
     */
    public function customPage($slug)
    {
        $data = CustomizeablePage::with('translation')->where('slug', $slug)->firstOrFail();

        return view('website.custom-page', compact('data'));
    }
}
