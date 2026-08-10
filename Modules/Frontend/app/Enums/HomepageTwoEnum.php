<?php

namespace Modules\Frontend\app\Enums;

enum HomepageTwoEnum: int {

    case THEME = 2;

    public static function sections(): array
    {
        return [
            [
                'name'           => 'top_header_section',
                'global_content' => [
                    'offer_status' => [
                        'type'    => 'select',
                        'options' => [
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ],
                        'value'   => 'active',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'offer_label' => '24% OFF',
                        'offer_text'  => 'Free shipping for orders over $99',
                        'address'     => '25+G6 London, UK',
                    ],
                    'ar' => [
                        'offer_label' => '24% OFF',
                        'offer_text'  => 'الشحن مجاني للطلبات أكثر من $99',
                        'address'     => '25+G6 لندن، الولايات المتحدة',
                    ],
                ],
            ],
            [
                'name'           => 'hero_section',
                'global_content' => [
                    'item_one_status'              => [
                        'type'    => 'select',
                        'options' => [
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ],
                        'value'   => 'active',
                    ],
                    'item_one_action_button_url'   => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                    'item_one_image'               => [
                        'type'  => 'file',
                        'value' => 'website/images/banner_2_img_1.webp',
                    ],
                    'item_one_price'               => [
                        'type'  => 'number',
                        'value' => '134.00',
                    ],
                    'item_two_status'              => [
                        'type'    => 'select',
                        'options' => [
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ],
                        'value'   => 'active',
                    ],
                    'item_two_action_button_url'   => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                    'item_two_image'               => [
                        'type'  => 'file',
                        'value' => 'website/images/banner_2_img_2.webp',
                    ],
                    'item_two_price'               => [
                        'type'  => 'number',
                        'value' => '144.00',
                    ],
                    'item_three_status'            => [
                        'type'    => 'select',
                        'options' => [
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ],
                        'value'   => 'active',
                    ],
                    'item_three_action_button_url' => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                    'item_three_image'             => [
                        'type'  => 'file',
                        'value' => 'website/images/banner_2_img_3.webp',
                    ],
                    'item_three_price'             => [
                        'type'  => 'number',
                        'value' => '140.00',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'item_one_subtitle'             => 'Hot Collection 2025',
                        'item_one_title'                => 'Streamlined & Resilient Design.',
                        'item_one_action_button_text'   => 'Shop Now',
                        'item_two_subtitle'             => 'New Collection 2025',
                        'item_two_title'                => 'Streamlined & Resilient Design.',
                        'item_two_action_button_text'   => 'Shop Now',
                        'item_three_subtitle'           => 'Super Collection 202',
                        'item_three_title'              => 'Streamlined & Resilient Design.',
                        'item_three_action_button_text' => 'Shop Now',

                    ],
                    'ar' => [
                        'item_one_subtilte'           => 'مجموعة ساعدة 2025',
                        'item_one_title'              => 'تصميم متقدم ومقابلة.',
                        'item_one_action_button_text' => 'تسوق الآن',
                        'item_two_subtilte'           => 'مجموعة جديدة 2025',
                        'item_two_title'              => 'تصميم متقدم ومقابلة.',
                        'item_two_action_button_text' => 'تسوق الآن',
                        'item_three_subtilte'         => 'مجموعة سوبر 202',
                        'item_three_title'            => 'تصميم متقدم ومقابلة.',
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
                        'title'     => 'Featured Categories',
                    ],
                    'ar' => [
                        'sub_title' => 'تسوق حسب الفئات',
                        'title'     => 'الفئات المميزة',
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
                    'limit'                 => [
                        'type'  => 'number',
                        'value' => 10,
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'title'     => 'Flash Deals',
                        'sub_title' => 'Today’s Deals',
                    ],
                    'ar' => [
                        'title'     => 'صفقات فلاش',
                        'sub_title' => 'الصفقات الاثني عشرية',
                    ],
                ],
            ],
            [
                'name'           => 'new_arrival_section',
                'global_content' => [
                    'new_arrival_image_one'        => [
                        'type'  => 'file',
                        'value' => 'website/images/new_arraival_2_img_1.webp',
                    ],
                    'new_arrival_one_action_url'   => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                    'new_arrival_image_two'        => [
                        'type'  => 'file',
                        'value' => 'website/images/new_arraival_2_img_2.webp',
                    ],
                    'new_arrival_two_action_url'   => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                    'new_arrival_image_three'      => [
                        'type'  => 'file',
                        'value' => 'website/images/new_arraival_2_img_3.webp',
                    ],
                    'new_arrival_three_action_url' => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'new_arrival_one_title'         => 'ICEMOOD Men\'s Shirt Casual 3 Button Basic Tee Quick T-Short Sleeve Fit T-shirt.',
                        'new_arrival_one_subtitle'      => 'Collection 2024',
                        'new_arrival_one_action_text'   => 'Shop Collection',
                        'new_arrival_two_title'         => 'Edinburgh Green Top Hooded Raincoat.',
                        'new_arrival_two_subtitle'      => 'Upcoming 2025',
                        'new_arrival_two_label'         => '56% OFF',
                        'new_arrival_two_action_text'   => 'Shop Collection',
                        'new_arrival_three_title'       => 'Child\'s Green Pique Cotton Polo Tee.',
                        'new_arrival_three_subtitle'    => 'Up To 44% OFF',
                        'new_arrival_three_action_text' => 'Shop Collection',
                    ],
                    'ar' => [
                        'new_arrival_one_title'         => 'جودة ماء مانشستر مانشتر مانشنشستر مانشستر مانشستر مانشستر م  ',
                        'new_arrival_one_subtitle'      => 'مجموعة 2024',
                        'new_arrival_one_action_text'   => 'تسوق المجموعة',
                        'new_arrival_two_title'         => 'إندونيسون غ غرفة غرفة غرفة غرفة غ',
                        'new_arrival_two_subtitle'      => 'قادمة 2025',
                        'new_arrival_two_label'         => '56% OFF',
                        'new_arrival_two_action_text'   => 'تسوق المجموعة',
                        'new_arrival_three_title'       => 'صغير جزر زر زر زر زر زر زر ز',
                        'new_arrival_three_subtitle'    => 'حتى 44% OFF',
                        'new_arrival_three_action_text' => 'تسوق المجموعة',
                    ],
                ],
            ],
            [
                'name'           => 'favorite_products_section',
                'global_content' => [
                    'limit' => [
                        'type'  => 'number',
                        'value' => 8,
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'sub_title' => 'Customer Favorites',
                        'title'     => 'Best Selling Products',
                    ],
                    'ar' => [
                        'sub_title' => 'منتجات العملاء المفضلة',
                        'title'     => 'المنتجات المبيعة',
                    ],
                ],
            ],
            [
                'name'           => 'discount_banner_section',
                'global_content' => [
                    'offer_start_time' => [
                        'type'  => 'date',
                        'value' => now(),
                    ],
                    'offer_end_time'   => [
                        'type'  => 'date',
                        'value' => now()->addDays(7),
                    ],
                    'image_one'        => [
                        'type'  => 'file',
                        'value' => 'website/images/discount_2_img_1.webp',
                    ],
                    'image_two'        => [
                        'type'  => 'file',
                        'value' => 'website/images/discount_2_img_2.webp',
                    ],
                    'action_url'       => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'title'       => 'Monthly discounts',
                        'sub_title'   => 'Seasonal Closeout Sale Up to 30% Savings.',
                        'description' => 'Limited time offer. The deal will expires on {March 18,2024} HURRY UP! ',
                        'action_text' => 'Shop Collection',
                    ],
                    'ar' => [
                        'title'       => 'عروض الشهرية',
                        'sub_title'   => 'عطلة الخصم الشهرية حتى 30% الخصم.',
                        'description' => 'Limited time offer. The deal will expires on {March 18,2024} HURRY UP! ',
                        'action_text' => 'تسوق المجموعة',
                    ],
                ],
            ],
            [
                'name'           => 'feature_products_section',
                'global_content' => [
                    'limit' => [
                        'type'  => 'number',
                        'value' => 4,
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'title'     => 'Featured Products',
                        'sub_title' => 'Shop By Best Product',
                    ],
                    'ar' => [
                        'title'     => 'المنتجات المميزة',
                        'sub_title' => 'تسوق حسب المنتجات المميزة',
                    ],
                ],
            ],
            [
                'name'           => 'call_to_action_section',
                'global_content' => [
                    'cta_image_one'      => [
                        'type'  => 'file',
                        'value' => 'website/images/summer_collection_1.webp',
                    ],
                    'cta_image_two'      => [
                        'type'  => 'file',
                        'value' => 'website/images/summer_collection_2.webp',
                    ],
                    'cta_image_discount' => [
                        'type'  => 'file',
                        'value' => 'website/images/discount_percentige.webp',
                    ],
                    'action_url'         => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'cta_one_title'    => 'Member Special: 24% OFF',
                        'cta_one_subtitle' => 'THE SUMMER 2023 COLLECTION',
                        'action_text'      => 'Shop Collection',
                        'description'      => 'Seasonal charm captured in summer’s latest collection.',
                    ],
                    'ar' => [
                        'cta_one_title'    => 'مميزات عضو: 24% OFF',
                        'cta_one_subtitle' => 'مجموعة السفرة 2023',
                        'action_text'      => 'تسوق المجموعة',
                        'description'      => 'مجموعة ساعدة مانشستر مانشتر مانشستر مانشستر مانشستر مانشستر م  ',
                    ],
                ],
            ],
            [
                'name'           => 'hot_deal_section',
                'global_content' => [
                    'limit'               => [
                        'type'  => 'number',
                        'value' => 3,
                    ],
                    'collection_type'     => [
                        'type'    => 'select',
                        'options' => [
                            'best_seller' => 'Best Seller Products',
                            'popular'     => 'Popular Products',
                            'featured'    => 'Featured Products',
                        ],
                        'value'   => 'best_seller',
                    ],
                    'hot_deal_image'      => [
                        'type'  => 'file',
                        'value' => 'website/images/product_combo_1.webp',
                    ],
                    'hot_deal_action_url' => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'hot_deal_title'       => 'Denton Hidden Snap Ultra Stretch.',
                        'hot_deal_sub_title'   => 'Collection 2024',
                        'hot_deal_action_text' => 'Shop Collection',
                        'title'                => 'Today\'s Hot Deals',
                        'subtitle'             => 'Best Featured Deals',
                    ],
                    'ar' => [
                        'hot_deal_title'       => 'Denton Hidden Snap Ultra Stretch.',
                        'hot_deal_sub_title'   => 'مجموعة 2024',
                        'hot_deal_action_text' => 'تسوق المجموعة',
                        'title'                => 'العروض المميزة',
                        'subtitle'             => 'العروض المميزة',
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
                        'title'     => 'Latest From Blogs',
                        'sub_title' => 'Our Latest News',
                    ],
                    'ar' => [
                        'title'     => 'احدث من المدونة',
                        'sub_title' => 'احدث الاخبار',
                    ],
                ],
            ],
            [
                'name'           => 'benefit_section',
                'global_content' => [
                    'area_one_icon'   => [
                        'type'  => 'file',
                        'value' => 'website/images/benefit_icon_1.webp',
                    ],
                    'area_two_icon'   => [
                        'type'  => 'file',
                        'value' => 'website/images/benefit_icon_2.webp',
                    ],
                    'area_three_icon' => [
                        'type'  => 'file',
                        'value' => 'website/images/benefit_icon_3.webp',
                    ],
                    'area_four_icon'  => [
                        'type'  => 'file',
                        'value' => 'website/images/benefit_icon_4.webp',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'area_one_title'       => 'Free Shipping',
                        'area_one_sub_title'   => 'Standard shipping for orders',
                        'area_two_title'       => 'Flexible Payment',
                        'area_two_sub_title'   => 'Pay with Credit Cards',
                        'area_three_title'     => '14 Day Returns',
                        'area_three_sub_title' => '30 Days for an Exchange',
                        'area_four_title'      => 'Premium Support',
                        'area_four_sub_title'  => 'All Outstanding Support',
                    ],
                    'ar' => [
                        'area_one_title'       => 'شحن مجاني',
                        'area_one_sub_title'   => 'شحن سعري لطلبات',
                        'area_two_title'       => 'الدفع المفتوح',
                        'area_two_sub_title'   => 'دفع ببطاقات الائتمان',
                        'area_three_title'     => '14 يومًا للمرتجع',
                        'area_three_sub_title' => '30 يومًا لاستبدال',
                        'area_four_title'      => 'دعم Premium',
                        'area_four_sub_title'  => 'جميع الدعم المميز',
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
                    'contact_email'         => [
                        'type'  => 'text',
                        'value' => "contact@topcommerce.com",
                    ],
                    'contact_number'        => [
                        'type'  => 'text',
                        'value' => "+670 413 90 762",
                    ],
                    'shop_pages_status'     => [
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
                    'newsletter_status'     => [
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
                        'shop_pages_title'    => 'Shop',
                        'help_center_title'   => 'Information',
                        'address'             => '1000 5th Ave, New York, NY 10028, United States.',
                        'newsletter_subtitle' => 'Nula element eulimid olio nec zeugita celestas arco quis lobortis.',
                    ],
                    'ar' => [
                        'shop_pages_title'    => 'Shop',
                        'help_center_title'   => 'معلومات',
                        'address'             => '1000 5th Ave, New York, NY 10028, الولايات المتحدة.',
                        'newsletter_subtitle' => 'Nula element eulimid olio nec zeugita celestas arco quis lobortis.',
                    ],
                ],
            ],
        ];
    }
}
