<?php

namespace Modules\Frontend\app\Enums;

enum HomepageOneEnum: int {
    case THEME = 1;

    public static function sections(): array
    {
        return [
            [
                'name'           => 'top_header_section',
                'global_content' => [
                    'offer_start_time'       => [
                        'type'  => 'date',
                        'value' => now()->format('Y-m-d'),
                    ],
                    'offer_end_time'         => [
                        'type'  => 'date',
                        'value' => now()->addDays(7)->format('Y-m-d'),
                    ],
                    'offer_link'             => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                    'offer_status'           => [
                        'type'    => 'select',
                        'options' => [
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ],
                        'value'   => 'active',
                    ],
                    'mega_menu_offer_status' => [
                        'type'    => 'select',
                        'options' => [
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ],
                        'value'   => 'active',
                    ],
                    'mega_menu_offer_link'   => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'title'                     => 'Step into a fashion wonderland',
                        'offer_link_text'           => 'Get Your Order',
                        'address'                   => '25+G6 London, UK',
                        'mega_menu_title'           => 'Up To - 35% Off',
                        'mega_menu_subtitle'        => 'Hot Deals',
                        'mega_menu_offer_link_text' => 'Shop Now',
                    ],
                    'ar' => [
                        'title'                     => 'ابدا في عالم الاثائر',
                        'offer_link_text'           => 'احصل على طلبك',
                        'address'                   => '25+G6 لندن، الولايات المتحدة',
                        'mega_menu_title'           => 'حتى - 35% خصم',
                        'mega_menu_subtitle'        => 'عروض سعرية',
                        'mega_menu_offer_link_text' => 'تسوق الآن',
                    ],
                ],
            ],
            [
                'name'           => 'hero_section',
                'global_content' => [
                    'action_button_url' => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                    'banner_image'      => [
                        'type'  => 'file',
                        'value' => 'website/images/banner_img.webp',
                    ],
                    'price'             => [
                        'type'  => 'number',
                        'value' => '39.00',
                    ],
                    'discount_price'    => [
                        'type'  => 'number',
                        'value' => '29.00',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'subtitle'           => 'Giant Discount Blitz!',
                        'title'              => 'Fresh Men’s Trends For The Season.',
                        'details'            => 'Quisque condimentum ante eu convallis sagittis sapien sapien orci nunc erat felis quam ex.',
                        'action_button_text' => 'Shop Now',
                    ],
                    'ar' => [
                        'subtitle'           => 'خصم ضخم!',
                        'title'              => 'اتجاهات رجالية جديدة لهذا الموسم.',
                        'details'            => 'وكالة هذا العام في جميع أنحاء العالم',
                        'action_button_text' => 'تسوق الآن',
                    ],
                ],
            ],
            [
                'name'           => 'featured_category_section',
                'global_content' => [
                    'limit' => [
                        'type'  => 'number',
                        'value' => 10,
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'sub_title' => 'Shop by Categories',
                        'title'     => 'Featured {Categories}',
                    ],
                    'ar' => [
                        'sub_title' => 'تسوق حسب الفئات',
                        'title'     => 'الفئات {المميزة}',
                    ],
                ],
            ],
            [
                'name'           => 'quick_shopping_section',
                'global_content' => [
                    'left_product_price'        => [
                        'type'  => 'number',
                        'value' => '56.00',
                    ],
                    'left_product_image'        => [
                        'type'  => 'file',
                        'value' => 'website/images/quick_shop_img_1.webp',
                    ],
                    'left_product_action_url'   => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                    'center_product_price'      => [
                        'type'  => 'number',
                        'value' => '46.00',
                    ],
                    'center_product_image'      => [
                        'type'  => 'file',
                        'value' => 'website/images/quick_shop_img_2.webp',
                    ],
                    'center_product_action_url' => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                    'right_product_price'       => [
                        'type'  => 'number',
                        'value' => '66.00',
                    ],
                    'right_product_image'       => [
                        'type'  => 'file',
                        'value' => 'website/images/quick_shop_img_3.webp',
                    ],
                    'right_product_action_url'  => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],

                ],
                'translations'   => [
                    'en' => [
                        'left_product_title'         => 'Fashion Cotton Lightweight Jacket.',
                        'left_product_price_text'    => 'Starting At :',
                        'left_product_action_text'   => 'Shop Now',
                        'center_product_title'       => 'Stgaubron Gaming Desktop PC',
                        'center_product_subtitle'    => 'Desktop PC',
                        'center_product_price_text'  => 'Starting At :',
                        'center_product_action_text' => 'Shop Now',
                        'right_product_title'        => 'Fashion Cotton Lightweight Jacket.',
                        'right_product_price_text'   => 'Starting At :',
                        'right_product_action_text'  => 'Shop Now',
                    ],
                    'ar' => [
                        'left_product_title'         => 'جاكيت سليم ليمون ورد',
                        'left_product_price_text'    => 'بدأ من :',
                        'left_product_action_text'   => 'تسوق الآن',
                        'center_product_title'       => 'كمبيوتر مكتبي للألعاب Stgaubron',
                        'center_product_price_text'  => 'بدأ من :',
                        'center_product_action_text' => 'تسوق الآن',
                        'right_product_title'        => 'جاكيت سليم ليمون ورد',
                        'right_product_price_text'   => 'بدأ من :',
                        'right_product_action_text'  => 'تسوق الآن',
                    ],
                ],
            ],
            [
                'name'           => 'best_selling_section',
                'global_content' => [
                    'limit' => [
                        'type'  => 'number',
                        'value' => 10,
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'title'     => 'Best Selling {Products}',
                        'sub_title' => 'Shop By Best Product',
                    ],
                    'ar' => [
                        'title'     => 'المنتجات {المبيعة}',
                        'sub_title' => 'تسوق حسب المنتجات المبيعة',
                    ],
                ],
            ],
            [
                'name' => 'flash_product_section',
            ],
            [
                'name'           => 'bundle_combo_section',
                'global_content' => [
                    'limit'                => [
                        'type'  => 'number',
                        'value' => 10,
                    ],
                    'collection_type'      => [
                        'type'    => 'select',
                        'options' => [
                            'best_seller' => 'Best Seller Products',
                            'popular'     => 'Popular Products',
                            'featured'    => 'Featured Products',
                        ],
                        'value'   => 'best_seller',
                    ],
                    'banner_product_image' => [
                        'type'  => 'file',
                        'value' => 'website/images/product_combo_1.webp',
                    ],
                    'banner_combo_image'   => [
                        'type'  => 'file',
                        'value' => 'website/images/product_combo_shape.webp',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'title'     => 'Product {collections}',
                        'sub_title' => 'Get Best sellers products',
                    ],
                    'ar' => [
                        'title'     => 'منتجات {المجموعات}',
                        'sub_title' => 'حصل على المنتجات المبيعة الافضل',
                    ],
                ],
            ],
            [
                'name'           => 'brand_section',
                'global_content' => [
                    'limit' => [
                        'type'  => 'number',
                        'value' => 10,
                    ],
                ],
            ],
            [
                'name'           => 'call_to_action_section',
                'global_content' => [
                    'cta_image_one' => [
                        'type'  => 'file',
                        'value' => 'website/images/cta_img1.webp',
                    ],
                    'cta_image_two' => [
                        'type'  => 'file',
                        'value' => 'website/images/cta_img2.webp',
                    ],
                    'action_url'    => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                    'product_price' => [
                        'type'  => 'number',
                        'value' => '58.00',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'cta_one_title'      => 'Brand: Apple Watch',
                        'cta_one_subtitle'   => 'Fitness Oxygen',
                        'action_text'        => 'Shop Collection',
                        'product_title'      => 'Stgaubron Gaming Desktop PC',
                        'product_price_text' => 'Starting At :',
                    ],
                    'ar' => [
                        'cta_one_title'      => 'ماركة: ساعة ابل',
                        'cta_one_subtitle'   => 'فيتنس',
                        'action_text'        => 'تسوق المجموعة',
                        'product_title'      => 'كمبيوتر مكتبي للألعاب Stgaubron',
                        'product_price_text' => 'بدأ من :',
                    ],
                ],
            ],
            [
                'name'           => 'feature_products_section',
                'global_content' => [
                    'limit' => [
                        'type'  => 'number',
                        'value' => 10,
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'title'     => 'Featured {Products}',
                        'sub_title' => 'Shop By Best Product',
                    ],
                    'ar' => [
                        'title'     => 'المنتجات {المميزة}',
                        'sub_title' => 'تسوق حسب المنتجات المميزة',
                    ],
                ],
            ],
            [
                'name'           => 'flash_deal_section',
                'global_content' => [
                    'flash_deal_start_date' => [
                        'type'  => 'date',
                        'value' => now()->subDays(2),
                    ],
                    'flash_deal_end_date'   => [
                        'type'  => 'date',
                        'value' => now()->addYear(2),
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'title'     => 'Flash {Deals}',
                        'sub_title' => 'Today’s Deals',
                    ],
                    'ar' => [
                        'title'     => 'صفقات {فلاش}',
                        'sub_title' => 'الصفقات الاثني عشرية',
                    ],
                ],
            ],
            [
                'name'         => 'testimonials_section',
                'translations' => [
                    'en' => [
                        'sub_title' => 'Testimonials',
                        'title'     => 'Clients {Feedback}',
                    ],
                    'ar' => [
                        'sub_title' => 'مراجعات العملاء',
                        'title'     => 'عملاؤنا {ملاحظات}',
                    ],
                ],
            ],
            [
                'name'           => 'blog_section',
                'global_content' => [
                    'limit' => [
                        'type'  => 'number',
                        'value' => 3,
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'title'     => 'Latest From {Blogs}',
                        'sub_title' => 'Our Latest News',
                    ],
                    'ar' => [
                        'title'     => 'احدث من {المدونة}',
                        'sub_title' => 'احدث الاخبار',
                    ],
                ],
            ],
            [
                'name'           => 'footer_section',
                'global_content' => [
                    'facebook_status'       => [
                        'type'    => 'select',
                        'options' => [
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ],
                        'value'   => 'active',
                    ],
                    'facebook_link'         => [
                        'type'  => 'text',
                        'value' => "https://www.facebook.com/",
                    ],
                    'x_status'              => [
                        'type'    => 'select',
                        'options' => [
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ],
                        'value'   => 'active',
                    ],
                    'x_link'                => [
                        'type'  => 'text',
                        'value' => "https://www.twitter.com/",
                    ],
                    'linkedin_status'       => [
                        'type'    => 'select',
                        'options' => [
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ],
                        'value'   => 'active',
                    ],
                    'linkedin_link'         => [
                        'type'  => 'text',
                        'value' => "https://www.linkedin.com/",
                    ],
                    'pinterest_status'      => [
                        'type'    => 'select',
                        'options' => [
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ],
                        'value'   => 'active',
                    ],
                    'pinterest_link'        => [
                        'type'  => 'text',
                        'value' => "https://www.pinterest.com/",
                    ],
                    'apple_store_status'    => [
                        'type'    => 'select',
                        'options' => [
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ],
                        'value'   => 'active',
                    ],
                    'apple_store_link'      => [
                        'type'  => 'text',
                        'value' => "https://www.appstore.com/",
                    ],
                    'google_store_status'   => [
                        'type'    => 'select',
                        'options' => [
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ],
                        'value'   => 'active',
                    ],
                    'google_store_link'     => [
                        'type'  => 'text',
                        'value' => "https://www.appstore.com/",
                    ],
                    'contact_email'         => [
                        'type'  => 'text',
                        'value' => "contact@topcommerce.com",
                    ],
                    'contact_number'        => [
                        'type'  => 'text',
                        'value' => "+670 413 90 762",
                    ],
                    'useful_pages_status'   => [
                        'type'    => 'select',
                        'options' => [
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ],
                        'value'   => 'active',
                    ],
                    'help_center_status'    => [
                        'type'    => 'select',
                        'options' => [
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ],
                        'value'   => 'active',
                    ],
                    'payment_gateway_image' => [
                        'type'  => 'file',
                        'value' => 'website/images/footer_2_payment_1.webp',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'useful_pages_title' => 'Useful Pages',
                        'help_center_title'  => 'Help Center',
                        'footer_subtitle'    => 'Nula element eulimid olio nec zeugita celestas arco quis lobortis.',
                        'address'            => '1000 5th Ave, New York, NY 10028, United States.',
                    ],
                    'ar' => [
                        'useful_pages_title' => 'صفحات مفيدة',
                        'help_center_title'  => 'مركز المساعدة',
                        'footer_subtitle'    => 'Nula element eulimid olio nec zeugita celestas arco quis lobortis.',
                        'address'            => '1000 5th Ave, New York, NY 10028, United States.',
                    ],
                ],
            ],
        ];
    }

    public static function loginSection()
    {
        return [
            'name'           => 'login_page',
            'global_content' => [
                'social_login_status' => [
                    'type'    => 'select',
                    'options' => [
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ],
                    'value'   => 'active',
                ],
                'logo_image'          => [
                    'type'  => 'file',
                    'value' => '/website/images/logo_3.webp',
                ],
                'login_image'         => [
                    'type'  => 'file',
                    'value' => 'website/images/login_img.webp',
                ],
            ],
        ];
    }

    public static function registerSection()
    {
        return [
            'name'           => 'register_page',
            'global_content' => [
                'social_login_status' => [
                    'type'    => 'select',
                    'options' => [
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ],
                    'value'   => 'active',
                ],
                'logo_image'          => [
                    'type'  => 'file',
                    'value' => '/website/images/logo_3.webp',
                ],
                'register_image'      => [
                    'type'  => 'file',
                    'value' => 'website/images/signup_img.webp',
                ],
            ],
        ];
    }

    public static function aboutUsPage()
    {
        return [
            'name'           => 'about_us_page',
            'global_content' => [
                'mission_status'        => [
                    'type'    => 'select',
                    'options' => [
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ],
                    'value'   => 'active',
                ],
                'mission_image'         => [
                    'type'  => 'file',
                    'value' => 'website/images/mission_img_1.webp',
                ],
                'mission_preview_image' => [
                    'type'  => 'file',
                    'value' => 'website/images/mission_img_2.webp',
                ],
                'vision_status'         => [
                    'type'    => 'select',
                    'options' => [
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ],
                    'value'   => 'active',
                ],
                'vision_image'          => [
                    'type'  => 'file',
                    'value' => 'website/images/vission_img_1.webp',
                ],
                'vision_preview_image'  => [
                    'type'  => 'file',
                    'value' => 'website/images/vission_img_2.webp',
                ],
                'testimonial_status'    => [
                    'type'    => 'select',
                    'options' => [
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ],
                    'value'   => 'active',
                ],
                'testimonial_limit'     => [
                    'type'  => 'number',
                    'value' => 10,
                ],

                'counter_status'        => [
                    'type'    => 'select',
                    'options' => [
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ],
                    'value'   => 'active',
                ],
                'blog_status'           => [
                    'type'    => 'select',
                    'options' => [
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ],
                    'value'   => 'active',
                ],
                'blog_limit'            => [
                    'type'  => 'number',
                    'value' => 3,
                ],
                'benefit_status'        => [
                    'type'    => 'select',
                    'options' => [
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ],
                    'value'   => 'active',
                ],
                'area_one_icon'         => [
                    'type'  => 'file',
                    'value' => 'website/images/benefit_icon_1.webp',
                ],
                'area_two_icon'         => [
                    'type'  => 'file',
                    'value' => 'website/images/benefit_icon_2.webp',
                ],
                'area_three_icon'       => [
                    'type'  => 'file',
                    'value' => 'website/images/benefit_icon_3.webp',
                ],
                'area_four_icon'        => [
                    'type'  => 'file',
                    'value' => 'website/images/benefit_icon_4.webp',
                ],
            ],
            'translations'   => [
                'en' => [
                    'mission_subtitle'        => 'Our Mission',
                    'mission_title'           => 'Responsible & {Long-lasting} Development.',
                    'mission_description'     => 'Suspendisse ultrices vel ipsum tristique iaculis Suspendisse ehicula nibh non sapien dictum ultrices Etiam pellentesque egestas leo.',
                    'mission_1'               => 'Nulla ex leo gravida eget consequat et tempus nitae risus',
                    'mission_2'               => 'Nunc in enim sed nunc eleifend facilisis',
                    'mission_3'               => 'Donec in enim sed nunc eleifend facilisis',
                    'mission_4'               => 'Donec in enim sed nunc eleifend facilisis',
                    'mission_background_text' => 'Mission',
                    'vision_subtitle'         => 'Our Vission',
                    'vision_title'            => 'Principled & {Eco-Conscious} Development.',
                    'vision_description'      => 'Suspendisse ultrices vel ipsum tristique iaculis Suspendisse ehicula nibh non sapien dictum ultrices Etiam pellentesque egestas leo.',
                    'vision_1'                => 'Nulla ex leo gravida eget consequat et tempus nitae risus',
                    'vision_2'                => 'Zaecenas accumsan dui et nisi ziverra suscipit eget lectus quis',
                    'vision_3'                => 'Tliquam nec ziverra nibh quis nulla quarries ullamcorpe',
                    'vision_4'                => 'Curabitu nunc nisl placerat sit amet arcu consectetur aliquet',
                    'vision_background_text'  => 'VISSION',
                    'testimonial_sub_title'   => 'Testimonials',
                    'testimonial_title'       => 'Clients {Feedback}',
                    'blog_title'              => 'Latest From {Blogs}',
                    'blog_sub_title'          => 'Our Latest News',
                    'area_one_title'          => 'Free Shipping',
                    'area_one_sub_title'      => 'Standard shipping for orders',
                    'area_two_title'          => 'Flexible Payment',
                    'area_two_sub_title'      => 'Pay with Credit Cards',
                    'area_three_title'        => '14 Day Returns',
                    'area_three_sub_title'    => '30 Days for an Exchange',
                    'area_four_title'         => 'Premium Support',
                    'area_four_sub_title'     => 'All Outstanding Support',
                ],
                'ar' => [
                    'mission_subtitle'        => 'مهمتنا',
                    'mission_title'           => 'تطوير مسؤول و{طويل الأمد}.',
                    'mission_description'     => 'تعليق التراكيب على شكل شبكة، وتعليق المركبة أسفلها بشكل لا يغير من الشكل العام. كذلك، فإن اختيار النموذج المناسب للحركة يعد أمراً مهماً.',
                    'mission_1'               => 'لا شيء إلا النمو المتسق والوقت المناسب',
                    'mission_2'               => 'الآن في الداخل، ثم تبسيط التنفيذ',
                    'mission_3'               => 'تم التنفيذ ببساطة وسلاسة داخل النظام',
                    'mission_4'               => 'تم التنفيذ ببساطة وسلاسة داخل النظام',
                    'mission_background_text' => 'مهمة',
                    'vision_subtitle'         => 'رؤيتنا',
                    'vision_title'            => 'تطوير قائم على المبادئ و{صديق للبيئة}.',
                    'vision_description'      => 'تعليق التراكيب على شكل شبكة، وتعليق المركبة أسفلها بشكل لا يغير من الشكل العام. كذلك، فإن اختيار النموذج المناسب للحركة يعد أمراً مهماً.',
                    'vision_1'                => 'لا شيء إلا النمو المتسق والوقت المناسب',
                    'vision_2'                => 'الاتساق والاعتماد على الذات هو ما نسعى إليه',
                    'vision_3'                => 'التركيز على الصداقة البيئية والجودة العالية',
                    'vision_4'                => 'التطوير من خلال التخطيط الفعال والابتكار',
                    'vision_background_text'  => 'رؤية',
                    'testimonial_sub_title'   => 'آراء العملاء',
                    'testimonial_title'       => '{تعليقات} العملاء',
                    'blog_title'              => 'آخر ما نُشر من {المدونات}',
                    'blog_sub_title'          => 'أحدث أخبارنا',
                    'area_one_title'          => 'شحن مجاني',
                    'area_one_sub_title'      => 'شحن قياسي على الطلبات',
                    'area_two_title'          => 'دفع مرن',
                    'area_two_sub_title'      => 'ادفع باستخدام بطاقات الائتمان',
                    'area_three_title'        => 'إرجاع خلال 14 يومًا',
                    'area_three_sub_title'    => '30 يومًا للتبديل',
                    'area_four_title'         => 'دعم مميز',
                    'area_four_sub_title'     => 'دعم ممتاز للجميع',
                ],
            ],
        ];
    }

    public static function contactUsPage()
    {
        return [
            'name'           => 'contact_us_page',
            'global_content' => [
                'contact_info_status'    => [
                    'type'    => 'select',
                    'options' => [
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ],
                    'value'   => 'active',
                ],
                'contact_us_email'       => [
                    'type'  => 'text',
                    'value' => 'contact@topcommerce.com',
                ],
                'contact_us_phone'       => [
                    'type'  => 'text',
                    'value' => 'Phone: 088 6578 654 87',
                ],
                'contact_us_form_status' => [
                    'type'    => 'select',
                    'options' => [
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ],
                    'value'   => 'active',
                ],
                'contact_image'          => [
                    'type'  => 'file',
                    'value' => 'website/images/contact_form_img.webp',
                ],
                'map_status'             => [
                    'type'    => 'select',
                    'options' => [
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ],
                    'value'   => 'active',
                ],
                'map_link'               => [
                    'type'  => 'text',
                    'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d58955.86762247907!2d88.3391639282542!3d22.551345723020553!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a0277a2e8448a01%3A0xfc7031bafe756ae4!2sMillennium%20Park%2C%20Kolkata!5e0!3m2!1sen!2sbd!4v1710672733871!5m2!1sen!2sbd',
                ],
            ],
            'translations'   => [
                'en' => [
                    'contact_office_address' => '7232 Broadway Suite 3087 Madison Heights, 57256',
                    'tema_up_message'        => 'Sed nec libero ante odio mauris pellentesque eget et neque.',

                ],
                'ar' => [
                    'contact_office_address' => '7232 Broadway Suite 3087 Madison Heights, 57256',
                    'tema_up_message'        => 'Sed nec libero ante odio mauris pellentesque eget et neque.',
                ],
            ],
        ];
    }
}
