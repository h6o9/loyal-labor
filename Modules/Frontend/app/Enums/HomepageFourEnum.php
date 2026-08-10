<?php

namespace Modules\Frontend\app\Enums;

enum HomepageFourEnum: int {
    case THEME = 4;

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
                    'product_one_image'       => [
                        'type'  => 'file',
                        'value' => 'website/images/banner_4_img_1.webp',
                    ],
                    'product_one_label_image' => [
                        'type'  => 'file',
                        'value' => 'website/images/offer_shape.webp',
                    ],
                    'product_two_image'       => [
                        'type'  => 'file',
                        'value' => 'website/images/banner_4_img_2.webp',
                    ],
                    'product_two_label_image' => [
                        'type'  => 'file',
                        'value' => 'website/images/offer_shape_2.webp',
                    ],
                    'action_link'             => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'title'              => 'Enhance a Seating {Configuration.}',
                        'label_one'          => 'Our Materials',
                        'label_one_text'     => 'Our Materials',
                        'label_two'          => 'Product Size',
                        'label_two_text'     => '60X138X22',
                        'label_three'        => 'Available In',
                        'label_three_text'   => 'Greay,Yellow',
                        'action_button_text' => 'Shop Collection',
                    ],
                    'ar' => [
                        'title'              => 'Enhance a Seating {Configuration.}',
                        'label_one'          => 'Our Materials',
                        'label_one_text'     => 'Our Materials',
                        'label_two'          => 'Product Size',
                        'label_two_text'     => '60X138X22',
                        'label_three'        => 'Available In',
                        'label_three_text'   => 'Greay,Yellow',
                        'action_button_text' => 'Shop Collection',
                    ],
                ],
            ],
            [
                'name'           => 'featured_category_section',
                'global_content' => [
                    'limit' => [
                        'type'  => 'number',
                        'value' => 6,
                    ],
                ],
                'translations'   => [],
            ],
            [
                'name'           => 'flash_deal_section',
                'global_content' => [
                    'limit'                 => [
                        'type'  => 'number',
                        'value' => 10,
                    ],
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
                'name'           => 'hot_deal_section',
                'global_content' => [
                    'deal_one_image'        => [
                        'type'  => 'file',
                        'value' => 'website/images/hot_deals_4_img_1.webp',
                    ],
                    'deal_one_button_url'   => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                    'deal_two_image'        => [
                        'type'  => 'file',
                        'value' => 'website/images/hot_deals_4_img_2.webp',
                    ],
                    'deal_two_label_image'  => [
                        'type'  => 'file',
                        'value' => 'website/images/offer_shape_3.webp',
                    ],
                    'deal_two_start_time'   => [
                        'type'  => 'date',
                        'value' => now(),
                    ],
                    'deal_two_end_time'     => [
                        'type'  => 'date',
                        'value' => now(),
                    ],
                    'deal_two_button_url'   => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                    'deal_three_image'      => [
                        'type'  => 'file',
                        'value' => 'website/images/hot_deals_4_img_3.webp',
                    ],
                    'deal_three_button_url' => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                    'deal_four_image'       => [
                        'type'  => 'file',
                        'value' => 'website/images/hot_deals_4_img_4.webp',
                    ],
                    'deal_four_button_url'  => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'deal_one_title'         => 'Office Home Office Dining Solid Material Sofa Frame Purple.',
                        'deal_one_subtitle'      => 'Hot Deal In Week',
                        'deal_one_button_text'   => 'Shop Now',
                        'deal_two_title'         => 'Seasonal Blowout Up To 30% Savings',
                        'deal_two_button_text'   => 'Shop Now',
                        'deal_three_title'       => 'Homco Modern Touch Fabric Leisure Club.',
                        'deal_three_subtitle'    => 'Club Chair',
                        'deal_three_button_text' => 'Shop Now',
                        'deal_four_title'        => 'Homco Modern Touch Fabric Leisure Club.',
                        'deal_four_subtitle'     => 'Club Chair',
                        'deal_four_button_text'  => 'Shop Now',
                    ],
                    'ar' => [
                        'deal_one_title'         => 'Office Home Office Dining Solid Material Sofa Frame Purple.',
                        'deal_one_subtitle'      => 'Hot Deal In Week',
                        'deal_one_button_text'   => 'Shop Now',
                        'deal_two_title'         => 'Seasonal Blowout Up To 30% Savings',
                        'deal_two_button_text'   => 'Shop Now',
                        'deal_three_title'       => 'Homco Modern Touch Fabric Leisure Club.',
                        'deal_three_subtitle'    => 'Club Chair',
                        'deal_three_button_text' => 'Shop Now',
                        'deal_four_title'        => 'Homco Modern Touch Fabric Leisure Club.',
                        'deal_four_subtitle'     => 'Club Chair',
                        'deal_four_button_text'  => 'Shop Now',
                    ],
                ],
            ],
            [
                'name'           => 'best_sales_section',
                'global_content' => [
                    'limit' => [
                        'type'  => 'number',
                        'value' => 8,
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'title'     => 'Best Selling Products',
                        'sub_title' => 'New Products',
                    ],
                    'ar' => [
                        'title'     => 'Best Selling Products',
                        'sub_title' => 'New Products',
                    ],
                ],
            ],
            [
                'name'           => 'discount_section',
                'global_content' => [
                    'video_thumbnail'     => [
                        'type'  => 'file',
                        'value' => 'website/images/process_4_bg_2.webp',
                    ],
                    'video_one_status'    => [
                        'type'    => 'select',
                        'value'   => 'active',
                        'options' => [
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ],
                    ],
                    'video_one_link'      => [
                        'type'  => 'text',
                        'value' => 'https://youtu.be/6h6b4LPq1Vw?si=gn8f4hZXpSKZr55e',
                    ],
                    'video_two_status'    => [
                        'type'    => 'select',
                        'value'   => 'active',
                        'options' => [
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ],
                    ],
                    'video_two_link'      => [
                        'type'  => 'text',
                        'value' => 'https://youtu.be/6h6b4LPq1Vw?si=gn8f4hZXpSKZr55e',
                    ],
                    'product_image'       => [
                        'type'  => 'file',
                        'value' => 'website/images/process_4_img_1.webp',
                    ],
                    'product_label_image' => [
                        'type'  => 'file',
                        'value' => 'website/images/offer_shape_3.webp',
                    ],
                    'process_one_icon'    => [
                        'type'  => 'file',
                        'value' => 'website/images/benefit_icon_1.webp',
                    ],
                    'process_two_icon'    => [
                        'type'  => 'file',
                        'value' => 'website/images/benefit_icon_2.webp',
                    ],
                    'process_three_icon'  => [
                        'type'  => 'file',
                        'value' => 'website/images/benefit_icon_3.webp',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'title'                  => 'The Future You Have Yet to Experience...!',
                        'process_one_title'      => 'Free Shipping',
                        'process_one_subtitle'   => 'Extra Shipping Benefit to Ensure Swift, Reliable and Stress-Free Deliveries.',
                        'process_two_title'      => 'Flexible Payment',
                        'process_two_subtitle'   => 'Extra Shipping Benefit to Ensure Swift, Reliable and Stress-Free Deliveries.',
                        'process_three_title'    => 'Premium Support',
                        'process_three_subtitle' => 'Extra Shipping Benefit to Ensure Swift, Reliable and Stress-Free Deliveries.',
                    ],
                    'ar' => [
                        'title'                  => 'The Future You Have Yet to Experience...!',
                        'process_one_title'      => 'Free Shipping',
                        'process_one_subtitle'   => 'Extra Shipping Benefit to Ensure Swift, Reliable and Stress-Free Deliveries.',
                        'process_two_title'      => 'Flexible Payment',
                        'process_two_subtitle'   => 'Extra Shipping Benefit to Ensure Swift, Reliable and Stress-Free Deliveries.',
                        'process_three_title'    => 'Premium Support',
                        'process_three_subtitle' => 'Extra Shipping Benefit to Ensure Swift, Reliable and Stress-Free Deliveries.',
                    ],
                ],
            ],
            [
                'name'           => 'top_selling_section',
                'global_content' => [
                    'action_url'        => [
                        'type'  => 'text',
                        'value' => '/products',
                    ],
                    'top_selling_price' => [
                        'type'  => 'number',
                        'value' => '58.00',
                    ],
                    'top_selling_image' => [
                        'type'  => 'file',
                        'value' => 'website/images/top_selling_img.webp',
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'title'                   => 'HomCom 34" Loveseat Sofa To Tufted Back.',
                        'sub_title'               => 'Top Selling Products',
                        'top_selling_price_label' => 'Starting price',
                        'action_button_text'      => 'Shop Now',
                    ],
                    'ar' => [
                        'title'                   => 'HomCom 34" Loveseat Sofa To Tufted Back.',
                        'sub_title'               => 'Top Selling Products',
                        'top_selling_price_label' => 'Starting price',
                        'action_button_text'      => 'Shop Now',
                    ],
                ],
            ],
            [
                'name'           => 'filtered_product_section',
                'global_content' => [
                    'product_image'       => [
                        'type'  => 'file',
                        'value' => 'website/images/buy_product_img.webp',
                    ],
                    'product_label_image' => [
                        'type'  => 'file',
                        'value' => 'website/images/chair_shape.webp',
                    ],
                    'product_sku'         => [
                        'type'  => 'text',
                        'value' => 'SKU-00000001',
                    ],
                ],
            ],
            [
                'name'           => 'blog_section',
                'global_content' => [
                    'limit' => [
                        'type'  => 'number',
                        'value' => 8,
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
                    'shop_time'             => [
                        'type'  => 'textarea',
                        'value' => "Monday - Friday : {8:00 AM - 6:00 PM}
                        Saturday : {10:00 AM - 6:00 PM}
                        Sunday: {Close}",
                    ],
                ],
                'translations'   => [
                    'en' => [
                        'hot_categories_title' => 'Useful Links',
                        'help_center_title'    => 'Place of interest',
                        'address'              => '1000 5th Ave, New York, NY 10028, United States.',
                        'newsletter_subtitle'  => 'Nula element eulimid olio nec zeugita celestas arco quis lobortis.',
                    ],
                    'ar' => [
                        'shop_pages_title'    => 'Hot Categories',
                        'help_center_title'   => 'Place of interest',
                        'address'             => '1000 5th Ave, New York, NY 10028, الولايات المتحدة.',
                        'newsletter_subtitle' => 'Nula element eulimid olio nec zeugita celestas arco quis lobortis.',
                        'shop_time'           => "Monday - Friday : {8:00 AM - 6:00 PM}
                        Saturday : {10:00 AM - 6:00 PM}
                        Sunday: {Close}",
                    ],
                ],
            ],
        ];
    }
}
