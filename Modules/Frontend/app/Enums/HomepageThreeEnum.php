<?php

namespace Modules\Frontend\app\Enums;

enum HomepageThreeEnum: int {
    case THEME = 3;

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
                    'action_button_url' => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                    'banner_image'      => [
                        'type'  => 'file',
                        'value' => 'website/images/banner_3_img.webp',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'subtitle'           => 'New Collection',
                        'title'              => 'Shopping Online Place Fresh Produce.',
                        'details'            => 'Nullam element eulimid olio nec zeugita mauri\'s celestas arco quis lobortis ipsum fringilla
                        Cras pellentesque nisl diam faucibus, at accumsan enim congue.',
                        'action_button_text' => 'Explore Products',
                    ],
                    'ar' => [
                        'subtitle'           => 'نوعية جديدة',
                        'title'              => 'موقع شحن على الإنترنت ممتازة المنتجات.',
                        'details'            => 'Nullam element eulimid olio nec zeugita mauri\'s celestas arco quis lobortis ipsum fringilla
                        Cras pellentesque nisl diam faucibus, at accumsan enim congue.',
                        'action_button_text' => 'تجربة المنتجات',
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
                'name'           => 'summer_sale_section',
                'global_content' => [
                    'image_one'       => [
                        'type'  => 'file',
                        'value' => 'website/images/summersale_3_img_1.webp',
                    ],
                    'image_two'       => [
                        'type'  => 'file',
                        'value' => 'website/images/summersale_3_img_2.webp',
                    ],
                    'action_link_one' => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                    'action_link_two' => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'sub_title_one'        => 'Fresh Vegetables',
                        'title_one'            => 'Home Delivery of Fresh Fruits & Vegetables.',
                        'action_link_text_one' => 'Shop Now',
                        'sub_title_two'        => 'Fresh Vegetables',
                        'title_two'            => 'Delicious Steaks From Our Top Chef.',
                        'action_link_text_two' => 'Shop Now',
                    ],
                    'ar' => [
                        'sub_title_one'        => 'Fresh Vegetables',
                        'title_one'            => 'Home Delivery of Fresh Fruits & Vegetables.',
                        'action_link_text_one' => 'Shop Now',
                        'sub_title_two'        => 'Fresh Vegetables',
                        'title_two'            => 'Delicious Steaks From Our Top Chef.',
                        'action_link_text_two' => 'Shop Now',
                    ],
                ],
            ],
            [
                'name'           => 'feature_products_section',
                'global_content' => [
                    'limit' => [
                        'type'  => 'number',
                        'value' => 8,
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'title'     => 'Featured Products',
                        'sub_title' => 'Best Seller',
                    ],
                    'ar' => [
                        'title'     => 'المنتجات المميزة',
                        'sub_title' => 'تسوق حسب المنتجات المميزة',
                    ],
                ],
            ],
            [
                'name'           => 'flash_deal_section',
                'global_content' => [
                    'limit' => [
                        'type'  => 'number',
                        'value' => 10,
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'title'       => 'Flash Deals',
                        'sub_title'   => 'Today’s Deals',
                        'description' => 'Nullam element eulimid olio nec zeugita mauri\'s celestas arco quis lobortis
                        Cras pellentesque nisl diam at accumsan.',
                    ],
                    'ar' => [
                        'title'       => 'صفقات فلاش',
                        'sub_title'   => 'الصفقات الاثني عشرية',
                        'description' => 'Nullam element eulimid olio nec zeugita mauri\'s celestas arco quis lobortis
                        Cras pellentesque nisl diam at accumsan.',
                    ],
                ],
            ],
            [
                'name'           => 'top_products_section',
                'global_content' => [
                    'limit'        => [
                        'type'  => 'number',
                        'value' => 10,
                    ],
                    'banner_image' => [
                        'type'  => 'file',
                        'value' => 'website/images/top_product_3_bg.webp',
                    ],
                    'banner_link'  => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'section_one_title' => 'Top Rated',
                        'section_two_title' => 'Top Sales',
                        'banner_title'      => 'Home Delivery of Fresh Fruits & Vegetables.',
                        'banner_subtitle'   => 'Fresh Vegetables',
                        'banner_link_text'  => 'Shop Now',

                    ],
                    'ar' => [
                        'section_one_title' => 'Top Rated',
                        'section_two_title' => 'Top Sales',
                        'banner_title'      => 'Home Delivery of Fresh Fruits & Vegetables.',
                        'banner_subtitle'   => 'Fresh Vegetables',
                        'banner_link_text'  => 'Shop Now',
                    ],
                ],
            ],
            [
                'name'           => 'popular_products_section',
                'global_content' => [
                    'limit'        => [
                        'type'  => 'number',
                        'value' => 10,
                    ],
                    'banner_image' => [
                        'type'  => 'file',
                        'value' => 'website/images/popular_product_3_img.webp',
                    ],
                    'banner_link'  => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'title'            => 'Trending Products',
                        'sub_title'        => 'Most Popular',
                        'banner_title'     => 'Leek Autumn Giant 45 Heirloom & Organic Canadian.',
                        'banner_subtitle'  => 'Get 35% off',
                        'banner_link_text' => 'Shop Now',
                    ],
                    'ar' => [
                        'title'            => 'Trending Products',
                        'sub_title'        => 'Most Popular',
                        'banner_title'     => 'Leek Autumn Giant 45 Heirloom & Organic Canadian.',
                        'banner_subtitle'  => 'Get35% off',
                        'banner_link_text' => 'Shop Now',
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
                        'title'     => 'Latest Blog & News',
                        'sub_title' => 'Read Our Blog',
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
                        'value' => 'website/images/benefit_icon_5.webp',
                    ],
                    'area_two_icon'   => [
                        'type'  => 'file',
                        'value' => 'website/images/benefit_icon_6.webp',
                    ],
                    'area_three_icon' => [
                        'type'  => 'file',
                        'value' => 'website/images/benefit_icon_7.webp',
                    ],
                    'area_four_icon'  => [
                        'type'  => 'file',
                        'value' => 'website/images/benefit_icon_8.webp',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'area_one_title'       => 'Free Shipping',
                        'area_one_sub_title'   => 'Standard shipping for orders',
                        'area_two_title'       => 'Helpline',
                        'area_two_sub_title'   => '+354 5875 547',
                        'area_three_title'     => '24x7 Support',
                        'area_three_sub_title' => 'Free For Customers',
                        'area_four_title'      => 'Returns',
                        'area_four_sub_title'  => '30 Days Free Exchanges',
                    ],
                    'ar' => [
                        'area_one_title'       => 'Free Shipping',
                        'area_one_sub_title'   => 'Standard shipping for orders',
                        'area_two_title'       => 'Helpline',
                        'area_two_sub_title'   => '+354 5875 547',
                        'area_three_title'     => '24x7 Support',
                        'area_three_sub_title' => 'Free For Customers',
                        'area_four_title'      => 'Returns',
                        'area_four_sub_title'  => '30 Days Free Exchanges',
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
}
