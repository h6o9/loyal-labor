<?php

namespace Modules\Product\database\seeders;

use App\Models\User;
use App\Models\Vendor;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\Frontend\app\Models\Section;
use Modules\Product\app\Models\Attribute;
use Modules\Product\app\Models\AttributeImage;
use Modules\Product\app\Models\AttributeValue;
use Modules\Product\app\Models\Brand;
use Modules\Product\app\Models\Category;
use Modules\Product\app\Models\Gallery;
use Modules\Product\app\Models\Product;
use Modules\Product\app\Models\ProductLabel;
use Modules\Product\app\Models\ProductTos;
use Modules\Product\app\Models\ProductTranslation;
use Modules\Product\app\Models\Tag;
use Modules\Product\app\Models\UnitType;
use Modules\Product\app\Models\Variant;
use Modules\Product\app\Models\VariantOption;
use Modules\Product\Database\Seeders\AttributeSeeder;
use Modules\Stock\app\Models\Stock;
use Modules\Tax\app\Models\Tax;

class ProductDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            LabelSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            UnitSeeder::class,
            TosSeeder::class,
            TagSeeder::class,
            AttributeSeeder::class,
        ]);

        $this->generateProducts();
    }

    public function generateProducts()
    {
        $brands         = Brand::pluck('id')->toArray();
        $vendors        = Vendor::pluck('id')->toArray();
        $unitTypes      = UnitType::pluck('id')->toArray();
        $returnPolicies = ProductTos::pluck('id')->toArray();
        $labels         = ProductLabel::pluck('id')->toArray();
        $taxes          = Tax::pluck('id')->toArray();
        $tags           = Tag::pluck('id')->toArray();

        $thumbnails = $this->getThumbnailFiles();
        $thumbCount = count($thumbnails);

        $realProductNames = [
            [
                'name'  => "Cotton Long Sleeve Shirt",
                'image' => 'website/images/home_1/product_1.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Men Moccasin Half-Loafer Shoe',
                'image' => 'website/images/home_1/product_2.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Sony Plus 32inch Smart Android Wi-Fi HD Led TV',
                'image' => 'website/images/home_1/product_3.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Saffola Active Oil (Rice Bran Oil) 5 Litre',
                'image' => 'website/images/home_1/product_4.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Visitor Fixed Chair CFT-RVC-03',
                'image' => 'website/images/home_1/product_5.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Vikan 27 Inch Smart Android Wi-Fi HD LED TV',
                'image' => 'website/images/home_1/product_6.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Best Coffee & Tea Table New Colour (19rx24h)',
                'image' => 'website/images/home_1/product_7.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Manfare Premium Sports T Shirt - Active Wear - MF-519',
                'image' => 'website/images/home_1/product_8.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Long Battery Life: JBL Tune 720BT Wireless Over-Ear Headphones',
                'image' => 'website/images/home_1/product_9.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Viewsonic 32inch Smart TV',
                'image' => 'website/images/home_1/product_10.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Tim Hortons Cookies Biscuit 150gm',
                'image' => 'website/images/home_1/product_11.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Jare Relax Adjustable Reclining Massage Chair',
                'image' => 'website/images/home_1/product_12.webp',
                'theme' => 1,
            ],
            [
                'name'  => "Cotton Classic Slim-Fit Shirt",
                'image' => 'website/images/home_1/product_1.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Men’s Stylish Moccasin Slip-On Shoe',
                'image' => 'website/images/home_1/product_2.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Sony Bravia 32" Smart Android HD LED TV',
                'image' => 'website/images/home_1/product_3.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Saffola Total Heart-Healthy Oil 5L',
                'image' => 'website/images/home_1/product_4.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Ergo Mesh Back Visitor Chair CFT-RVC-07',
                'image' => 'website/images/home_1/product_5.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Vikan Pro 27" Android TV With Voice Remote',
                'image' => 'website/images/home_1/product_6.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Modern Coffee Table With Glass Top (20x25h)',
                'image' => 'website/images/home_1/product_7.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Manfare Dri-Fit Sports Tee - MF-520 Edition',
                'image' => 'website/images/home_1/product_8.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'JBL 720BT+ Over-Ear Wireless Headphones',
                'image' => 'website/images/home_1/product_9.webp',
                'theme' => 1,
            ],
            [
                'name'  => 'Little Kid Winter Jacket',
                'image' => 'website/images/home_2/product_16.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Boy Winter Jacket',
                'image' => 'website/images/home_2/product_1.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Men Slim Fit Cotton Pants',
                'image' => 'website/images/home_2/product_2.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Women Floral Print Summer Dress',
                'image' => 'website/images/home_2/product_3.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Girl Cute Hoodie With Ears',
                'image' => 'website/images/home_2/product_4.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Men Formal Shirt - Navy Blue',
                'image' => 'website/images/home_2/product_5.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Women High-Waist Palazzo Pants',
                'image' => 'website/images/home_2/product_6.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Boy Graphic Printed T-Shirt',
                'image' => 'website/images/home_2/product_7.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Girl Princess Party Frock',
                'image' => 'website/images/home_2/product_8.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Men Zipper Hoodie - Grey',
                'image' => 'website/images/home_2/product_9.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Women Ethnic Kurti with Embroidery',
                'image' => 'website/images/home_2/product_10.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Boy Stretchable Denim Jeans',
                'image' => 'website/images/home_2/product_11.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Girl Floral Printed Leggings',
                'image' => 'website/images/home_2/product_12.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Men Casual Checked Shirt',
                'image' => 'website/images/home_2/product_13.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Women Chiffon Long Sleeve Top',
                'image' => 'website/images/home_2/product_14.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Boy Sweatshirt With Cartoon Print',
                'image' => 'website/images/home_2/product_15.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Girl Denim Overall Jumpsuit',
                'image' => 'website/images/home_2/product_16.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Men Sports Jogger Pants',
                'image' => 'website/images/home_2/product_17.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Women Knitted Winter Sweater',
                'image' => 'website/images/home_2/product_18.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Girl Puff Sleeve Cotton Top',
                'image' => 'website/images/home_2/product_10.webp',
                'theme' => 2,
            ],
            [
                'name'  => 'Fresh Red Apples (1kg)',
                'image' => 'website/images/home_3/product_1.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Green Kacha Kola (Raw Banana) 1 Dozen',
                'image' => 'website/images/home_3/product_2.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Deshi Chicken – Whole (Skin Off) 1kg',
                'image' => 'website/images/home_3/product_3.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Teer Soyabean Oil 5 Litres',
                'image' => 'website/images/home_3/product_4.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Ispahani Mirzapore Best Leaf Tea 400g',
                'image' => 'website/images/home_3/product_5.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Fresh Carrots (Organic) 500g',
                'image' => 'website/images/home_3/product_6.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Golden Harvest Atta – 2kg Pack',
                'image' => 'website/images/home_3/product_7.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Beef Bone-In Premium Cut 1kg',
                'image' => 'website/images/home_3/product_8.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Green Chillies – Local Fresh 250g',
                'image' => 'website/images/home_3/product_9.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Olitalia Extra Virgin Olive Oil 1L',
                'image' => 'website/images/home_3/product_10.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Ponni Rice (Short Grain) – 5kg',
                'image' => 'website/images/home_3/product_11.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Fresh Tomatoes – Deshi 1kg',
                'image' => 'website/images/home_3/product_12.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Premium Black Tea Leaves – 200g Tin',
                'image' => 'website/images/home_3/product_13.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Aar Fish Steak Cut – 500g',
                'image' => 'website/images/home_3/product_14.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Fresh Spinach Bundle – 250g',
                'image' => 'website/images/home_3/product_15.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Sunflower Cooking Oil – 2L Bottle',
                'image' => 'website/images/home_3/product_16.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Raw Jackfruit (Echor) – 1 Piece',
                'image' => 'website/images/home_3/product_17.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Loose Lemons – 5 Pieces',
                'image' => 'website/images/home_3/product_18.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Mutton Bone-In Premium Cut 1kg',
                'image' => 'website/images/home_3/product_8.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Red Chillies – Local Fresh 250g',
                'image' => 'website/images/home_3/product_9.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Miako Extra Virgin Olive Oil 1L',
                'image' => 'website/images/home_3/product_10.webp',
                'theme' => 3,
            ],
            [
                'name'  => 'Rolling Chair with Adjustable Height',
                'image' => 'website/images/home_4/product_1.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'Modern Glass Coffee Table - Black Frame',
                'image' => 'website/images/home_4/product_2.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'Ergonomic Mesh Office Chair with Armrests',
                'image' => 'website/images/home_4/product_3.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'Wooden Study Table with Drawers',
                'image' => 'website/images/home_4/product_4.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'Premium Leather Executive Chair',
                'image' => 'website/images/home_4/product_5.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'Round Side Table – Natural Wood Finish',
                'image' => 'website/images/home_4/product_6.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'High Back Reclining Office Chair',
                'image' => 'website/images/home_4/product_7.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'Minimalist Laptop Table with Folding Legs',
                'image' => 'website/images/home_4/product_8.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'Visitor Chair with Chrome Frame',
                'image' => 'website/images/home_4/product_9.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'Dining Table – 4 Seater, Walnut Finish',
                'image' => 'website/images/home_4/product_10.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'Armless Stackable Plastic Chair',
                'image' => 'website/images/home_4/product_11.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'Multi-Layer Foldable Study Table',
                'image' => 'website/images/home_4/product_12.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'Revolving Computer Chair with Headrest',
                'image' => 'website/images/home_4/product_13.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'Kids Activity Table with Storage Box',
                'image' => 'website/images/home_4/product_14.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'Cafe-Style Bistro Table – Round Top',
                'image' => 'website/images/home_4/product_15.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'Mid-Back PU Leather Office Chair',
                'image' => 'website/images/home_4/product_16.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'Foldable Picnic Table – 2 Person',
                'image' => 'website/images/home_4/product_3.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'Rocking Chair – Wooden Classic Style',
                'image' => 'website/images/home_4/product_8.webp',
                'theme' => 4,
            ],
            [
                'name'  => 'Study Desk with Bookshelf Rack',
                'image' => 'website/images/home_4/product_2.webp',
                'theme' => 4,
            ],
        ];

        $groupedProducts = collect($realProductNames)->groupBy('theme');

        foreach ($groupedProducts as $theme => $products) {
            $categories = Category::where('theme', $theme)->pluck('id')->toArray();
            foreach ($products as $realProductName) {
                $name  = str($realProductName['name'])->limit(50, '');
                $image = $realProductName['image'];
                $theme = $realProductName['theme'];

                $newProduct                                   = new Product();
                $newProduct->slug                             = generateUniqueSlug($name, Product::class, 'slug', true);
                $newProduct->brand_id                         = fake()->randomElement($brands);
                $newProduct->vendor_id                        = fake()->randomElement($vendors);
                $newProduct->unit_type_id                     = fake()->randomElement($unitTypes);
                $newProduct->thumbnail_image                  = $image;
                $newProduct->video_link                       = 'https://www.youtube.com/watch?v=' . Str::random(10);
                $newProduct->is_cash_delivery                 = fake()->boolean();
                $newProduct->is_return                        = fake()->boolean();
                $newProduct->return_policy_id                 = fake()->randomElement($returnPolicies);
                $newProduct->is_featured                      = fake()->boolean(90);
                $newProduct->is_popular                       = fake()->boolean(90);
                $newProduct->is_best_selling                  = fake()->boolean(90);
                $newProduct->allow_checkout_when_out_of_stock = fake()->boolean();
                $newProduct->price                            = fake()->randomFloat(2, 100, 2000);

                $newProduct->offer_price_type = fake()->randomElement(['fixed', 'percentage']);

                if ($newProduct->offer_price_type == 'percentage') {
                    $newProduct->offer_price = fake()->randomFloat(2, 0, 50);
                } else {
                    $newProduct->offer_price = fake()->randomFloat(2, max(0, $newProduct->price - 70), $newProduct->price);
                }

                $newProduct->offer_price_start = now();
                $newProduct->offer_price_end   = now()->addDays(rand(5, 15));
                $newProduct->manage_stock      = fake()->boolean(99);
                $newProduct->stock_status      = fake()->randomElement(['in_stock', 'out_of_stock']);
                $newProduct->sku               = generateSku();
                $newProduct->barcode           = generateUniqueBarcode();
                $newProduct->status            = 1;
                $newProduct->is_approved       = 1;
                $newProduct->length            = fake()->randomFloat(2, 5, 100);
                $newProduct->wide              = fake()->randomFloat(2, 5, 100);
                $newProduct->height            = fake()->randomFloat(2, 5, 100);
                $newProduct->weight            = fake()->randomFloat(2, 0.5, 10);
                $newProduct->viewed            = rand(0, 500);
                $newProduct->theme             = $theme;
                $newProduct->save();

                if ($status = fake()->boolean(45)) {

                    $originalPrice  = $newProduct->price;
                    $discountFactor = fake()->randomFloat(2, 0.1, 0.5);
                    $flashDealPrice = round($originalPrice * (1 - $discountFactor), 2);
                    if ($flashDealPrice >= $originalPrice) {
                        $flashDealPrice = $originalPrice - 1;
                    }

                    $newProduct->is_flash_deal    = $status;
                    $newProduct->flash_deal_start = now();
                    $newProduct->flash_deal_end   = now()->addDays(rand(20, 300));
                    $newProduct->flash_deal_price = $flashDealPrice;
                    $newProduct->flash_deal_qty   = rand(3, 50);
                    $newProduct->save();
                }

                foreach (allLanguages() as $lang) {
                    $faker = fake($lang->code ?? 'en_US');

                    ProductTranslation::create([
                        'product_id'        => $newProduct->id,
                        'lang_code'         => $lang->code,
                        'name'              => $name->title()->toString(),
                        'description'       => $faker->paragraphs(rand(4, 7), true),
                        'short_description' => str($faker->sentence(rand(6, 12)))->limit(191, '')->toString(),
                        'seo_title'         => "SEO Title $theme - $lang->name",
                        'seo_description'   => "SEO Description for $theme in $lang->name.",
                    ]);
                }

                $newProduct->categories()->attach(fake()->randomElements($categories, rand(1, 6)));

                $newProduct->labels()->attach(fake()->randomElements($labels, rand(1, 3)));

                $newProduct->taxes()->attach(fake()->randomElements($taxes, rand(1, 2)));

                $newProduct->tags()->attach(fake()->randomElements($tags, rand(2, 4)));

                if ($newProduct->manage_stock) {
                    Stock::create([
                        'product_id' => $newProduct->id,
                        'quantity'   => rand(10, 300),
                        'sku'        => $newProduct->sku,
                        'type'       => null,
                    ]);
                }

                $fakeImages = Arr::random($thumbnails, 6);

                foreach ($fakeImages as $imagePath) {
                    $productImage             = new Gallery;
                    $productImage->product_id = $newProduct->id;
                    $productImage->path       = $imagePath;
                    $productImage->save();
                }

                if (fake()->boolean(30)) {
                    $this->generateVariants($newProduct);
                }

                $this->createReviews($newProduct);
            }
        }

        $this->updateHomeThreeData();

        $this->updateHomeFourSectionData();
    }

    private function updateHomeFourSectionData()
    {
        $sku = Product::where('theme', 4)->published()->first()->sku ?? 'SKU-00000001';

        Section::where('home_id', 4)
            ->update([
                'global_content->product_sku->value' => $sku,
            ]);
    }

    private function updateHomeThreeData()
    {
        $today = now();

        $randomFlashDealImages = [
            'website/images/latest_deals_3_img_1.webp',
            'website/images/latest_deals_3_img_2.webp',
        ];

        $latestFlashDealProducts = Product::where('theme', 3)
            ->where('is_flash_deal', 1)
            ->whereDate('flash_deal_start', '<=', $today)
            ->whereDate('flash_deal_end', '>=', $today)
            ->published()
            ->latest()
            ->take(2)
            ->get();

        foreach ($latestFlashDealProducts as $product) {
            $product->flash_deal_image = $randomFlashDealImages[array_rand($randomFlashDealImages)];
            $product->save();
        }
    }

    /**
     * @return null
     */
    public function generateVariants($product)
    {
        // $sizeAttr  = Attribute::where('slug', 'size')->first();
        // $colorAttr = Attribute::where('slug', 'color')->first();

        // if (!$sizeAttr || !$colorAttr) {
        //     dump('Size or Color attribute not found.');

        //     return;
        // }

        // $sizes = AttributeValue::with(['translations' => function ($query) {
        //     $query->where('lang_code', 'en');
        // }])
        //     ->where('attribute_id', $sizeAttr->id)
        //     ->get()
        //     ->pluck('id', 'translations.0.name')
        //     ->toArray();

        // $colors = AttributeValue::with(['translations' => function ($query) {
        //     $query->where('lang_code', 'en');
        // }])
        //     ->where('attribute_id', $colorAttr->id)
        //     ->get()
        //     ->pluck('id', 'translations.0.name')
        //     ->toArray();

        // $skuBase = $product->sku;

        // $thumbnails = $this->getThumbnailFiles();
        // $thumbCount = count($thumbnails);

        // $variantId = 1;
        // foreach ($sizes as $sizeName => $sizeId) {
        //     foreach ($colors as $colorName => $colorId) {
        //         $sku = "{$sizeName}-{$colorName}-$skuBase";

        //         $minPrice = fake()->randomFloat(2, max(0, $product->price - 300), $product->price);
        //         $maxPrice = fake()->randomFloat(2, $minPrice, $product->price);

        //         $variant = Variant::create([
        //             'product_id'        => $product->id,
        //             'price'             => fake()->randomFloat(2, $minPrice, $maxPrice),
        //             'offer_price'       => null,
        //             'offer_price_type'  => 'fixed',
        //             'offer_price_start' => null,
        //             'offer_price_end'   => null,
        //             'is_default'        => ($variantId == 1 ? 1 : 0),
        //             'status'            => 1,
        //             'image'             => $thumbnails[rand(1, $thumbCount - 1)],
        //             'sku'               => $sku,
        //         ]);

        //         if ($product->manage_stock) {
        //             Stock::create([
        //                 'product_id' => $product->id,
        //                 'quantity'   => rand(10, 100),
        //                 'sku'        => $variant->sku,
        //                 'type'       => 'variant',
        //             ]);
        //         }

        //         VariantOption::create([
        //             'variant_id'         => $variant->id,
        //             'attribute_id'       => $sizeAttr->id,
        //             'attribute_value_id' => $sizeId,
        //         ]);

        //         VariantOption::create([
        //             'variant_id'         => $variant->id,
        //             'attribute_id'       => $colorAttr->id,
        //             'attribute_value_id' => $colorId,
        //         ]);

        //         if (rand(0, 1)) {
        //             AttributeImage::firstOrCreate([
        //                 'product_id'         => $product->id,
        //                 'attribute_id'       => $colorAttr->id,
        //                 'attribute_value_id' => $colorId,
        //             ], [
        //                 'image' => $thumbnails[rand(1, $thumbCount - 1)],
        //             ]);
        //         }

        //         $variantId++;
        //     }
        // }

        $sizeAttr  = Attribute::where('slug', 'size')->first();
        $colorAttr = Attribute::where('slug', 'color')->first();

        if (!$sizeAttr || !$colorAttr) {
            dump('Size or Color attribute not found.');
            return;
        }

        $sizes = AttributeValue::with(['translations' => function ($query) {
            $query->where('lang_code', 'en');
        }])
            ->where('attribute_id', $sizeAttr->id)
            ->get()
            ->pluck('id', 'translations.0.name')
            ->toArray();

        $colors = AttributeValue::with(['translations' => function ($query) {
            $query->where('lang_code', 'en');
        }])
            ->where('attribute_id', $colorAttr->id)
            ->get()
            ->pluck('id', 'translations.0.name')
            ->toArray();

        $skuBase = $product->sku;

        $thumbnails = $this->getThumbnailFiles();
        $thumbCount = count($thumbnails);

        // ✅ Generate all possible unique combinations
        $combinations = [];
        foreach ($sizes as $sizeName => $sizeId) {
            foreach ($colors as $colorName => $colorId) {
                $combinations[] = [
                    'sizeName'  => $sizeName,
                    'sizeId'    => $sizeId,
                    'colorName' => $colorName,
                    'colorId'   => $colorId,
                ];
            }
        }

        // ✅ Shuffle and pick 2–6 random unique combinations
        shuffle($combinations);
        $selectedCombinations = array_slice($combinations, 0, rand(2, min(6, count($combinations))));

        $variantId = 1;

        foreach ($selectedCombinations as $combo) {
            $sku = "{$combo['sizeName']}-{$combo['colorName']}-$skuBase";

            $minPrice = fake()->randomFloat(2, max(0, $product->price - 300), $product->price);
            $maxPrice = fake()->randomFloat(2, $minPrice, $product->price);

            $variant = Variant::create([
                'product_id'        => $product->id,
                'price'             => fake()->randomFloat(2, $minPrice, $maxPrice),
                'offer_price'       => null,
                'offer_price_type'  => 'fixed',
                'offer_price_start' => null,
                'offer_price_end'   => null,
                'is_default'        => ($variantId == 1 ? 1 : 0),
                'status'            => 1,
                'image'             => $thumbnails[rand(1, $thumbCount - 1)],
                'sku'               => $sku,
            ]);

            if ($product->manage_stock) {
                Stock::create([
                    'product_id' => $product->id,
                    'quantity'   => rand(10, 100),
                    'sku'        => $variant->sku,
                    'type'       => 'variant',
                ]);
            }

            VariantOption::create([
                'variant_id'         => $variant->id,
                'attribute_id'       => $sizeAttr->id,
                'attribute_value_id' => $combo['sizeId'],
            ]);

            VariantOption::create([
                'variant_id'         => $variant->id,
                'attribute_id'       => $colorAttr->id,
                'attribute_value_id' => $combo['colorId'],
            ]);

            if (rand(0, 1)) {
                AttributeImage::firstOrCreate([
                    'product_id'         => $product->id,
                    'attribute_id'       => $colorAttr->id,
                    'attribute_value_id' => $combo['colorId'],
                ], [
                    'image' => $thumbnails[rand(1, $thumbCount - 1)],
                ]);
            }

            $variantId++;
        }
    }

    /**
     * @param $product
     */
    private function createReviews($product)
    {
        $faker = Faker::create();

        for ($i = 0; $i < rand(5, 20); $i++) {
            $rating = rand(1, 5);

            $product->reviews()->create([
                'user_id'   => fake()->randomElement(User::pluck('id')->toArray()),
                'vendor_id' => fake()->randomElement(Vendor::pluck('id')->toArray()),
                'order_id'  => fake()->randomElement([1, 2, 3, 4]),
                'rating'    => $rating,
                'review'    => $faker->sentence(rand(5, 15)),
                'status'    => fake()->boolean(80),
            ]);
        }
    }

    /**
     * @return mixed
     */
    public function getThumbnailFiles()
    {
        $path = public_path('website/images/products');

        $files = File::files($path);

        return collect($files)
            ->filter(function ($file) {
                return $file->getExtension() === 'webp';
            })
            ->map(function ($file) {
                return 'website/images/products/' . $file->getFilename();
            })
            ->values()
            ->toArray();
    }
}
