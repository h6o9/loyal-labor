<?php

namespace Modules\CustomMenu\database\seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Modules\CustomMenu\app\Enums\DefaultMenusEnum;
use Modules\CustomMenu\app\Models\Menu;
use Modules\CustomMenu\app\Models\MenuItem;
use Modules\CustomMenu\app\Models\MenuItemTranslation;
use Modules\CustomMenu\app\Models\MenuTranslation;

class CustomMenuDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        function processMenuItems($menuItems, $menuId, $parentId = 0)
        {
            foreach ($menuItems as $item) {
                $menuItem            = new MenuItem;
                $menuItem->label     = $item['translations'][0]['label'];
                $menuItem->link      = $item['link'];
                $menuItem->menu_id   = $menuId;
                $menuItem->parent_id = $parentId;
                $menuItem->sort      = $item['sort'];

                if ($menuItem->save()) {
                    foreach ($item['translations'] as $translate_item) {
                        MenuItemTranslation::create([
                            'menu_item_id' => $menuItem->id,
                            'lang_code'    => $translate_item['lang_code'],
                            'label'        => $translate_item['label'],
                        ]);
                    }

                    if (isset($item['menu_items']) && is_array($item['menu_items'])) {
                        processMenuItems($item['menu_items'], $menuId, $menuItem->id);
                    }
                }
            }
        }
        // Menu list
        $menu_list = [
            [
                'slug'         => DefaultMenusEnum::MAIN_MENU->value,
                'translations' => [
                    ['lang_code' => 'en', 'name' => 'Main Menu'],
                    ['lang_code' => 'ar', 'name' => 'القائمة الرئيسية'],
                ],
                'menu_items'   => [
                    [
                        'link'         => '/',
                        'sort'         => 0,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Home'],
                            ['lang_code' => 'ar', 'label' => 'بيت'],
                        ],
                    ],
                    [
                        'link'         => '/shops',
                        'sort'         => 1,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Shops'],
                            ['lang_code' => 'ar', 'label' => 'متاجر'],
                        ],
                    ],
                    [
                        'link'         => '/products',
                        'sort'         => 3,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Products'],
                            ['lang_code' => 'ar', 'label' => 'منتجات'],
                        ],
                    ],
                    [
                        'link'         => '/categories',
                        'sort'         => 4,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Categories'],
                            ['lang_code' => 'ar', 'label' => 'فئات'],
                        ],
                    ],
                    [
                        'link'         => '/contact-us',
                        'sort'         => 5,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Contact Us'],
                            ['lang_code' => 'ar', 'label' => 'اتصل بنا'],
                        ],
                    ],
                    [
                        'link'         => '#',
                        'sort'         => 6,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Pages'],
                            ['lang_code' => 'ar', 'label' => 'صفحات مخصصة'],
                        ],
                        'menu_items'   => [
                            [
                                'link'         => '/about-us',
                                'sort'         => 1,
                                'translations' => [
                                    ['lang_code' => 'en', 'label' => 'About Us'],
                                    ['lang_code' => 'ar', 'label' => 'من نحن'],
                                ],
                            ],
                            [
                                'link'         => '/privacy-policy',
                                'sort'         => 2,
                                'translations' => [
                                    ['lang_code' => 'en', 'label' => 'Privacy Policy'],
                                    ['lang_code' => 'ar', 'label' => 'سياسة الخصوصية'],
                                ],
                            ],
                            [
                                'link'         => '/terms-and-conditions',
                                'sort'         => 3,
                                'translations' => [
                                    ['lang_code' => 'en', 'label' => 'Terms and Conditions'],
                                    ['lang_code' => 'ar', 'label' => 'الشروط والأحكام'],
                                ],
                            ],
                            [
                                'link'         => '/return-policy',
                                'sort'         => 4,
                                'translations' => [
                                    ['lang_code' => 'en', 'label' => 'Return Policy'],
                                    ['lang_code' => 'ar', 'label' => 'سياسة الإرجاع'],
                                ],
                            ],
                            [
                                'link'         => '/faq',
                                'sort'         => 5,
                                'translations' => [
                                    ['lang_code' => 'en', 'label' => 'FAQ'],
                                    ['lang_code' => 'ar', 'label' => 'الأسئلة الشائعة'],
                                ],
                            ],
                            [
                                'link'         => '/join-as-seller',
                                'sort'         => 5,
                                'translations' => [
                                    ['lang_code' => 'en', 'label' => 'Join as Seller'],
                                    ['lang_code' => 'ar', 'label' => 'انضم كبائع'],
                                ],
                            ],
                            [
                                'link'         => '/brands',
                                'sort'         => 6,
                                'translations' => [
                                    ['lang_code' => 'en', 'label' => 'Brands'],
                                    ['lang_code' => 'ar', 'label' => 'علامات تجارية'],
                                ],
                            ],
                            [
                                'link'         => '/blogs',
                                'sort'         => 6,
                                'translations' => [
                                    ['lang_code' => 'en', 'label' => 'Blogs'],
                                    ['lang_code' => 'ar', 'label' => 'مدونات'],
                                ],
                            ],
                            [
                                'link'         => '/track-order',
                                'sort'         => 7,
                                'translations' => [
                                    ['lang_code' => 'en', 'label' => 'Track Order'],
                                    ['lang_code' => 'ar', 'label' => 'تتبع الطلب'],
                                ],
                            ],
                            [
                                'link'         => '/flash-deals',
                                'sort'         => 8,
                                'translations' => [
                                    ['lang_code' => 'en', 'label' => 'Flash Deals'],
                                    ['lang_code' => 'ar', 'label' => 'عروض فلاش'],
                                ],
                            ],
                            [
                                'link'         => '/gift-cards',
                                'sort'         => 9,
                                'translations' => [
                                    ['lang_code' => 'en', 'label' => 'Gift Cards'],
                                    ['lang_code' => 'ar', 'label' => 'بطاقات الهدايا'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'slug'         => DefaultMenusEnum::FOOTER_MENU_ONE->value,
                'translations' => [
                    ['lang_code' => 'en', 'name' => 'Footer Menu One'],
                    ['lang_code' => 'ar', 'name' => 'القائمة السفلية الأولى'],
                ],
                'menu_items'   => [
                    [
                        'link'         => '/about-us',
                        'sort'         => 0,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'About Us'],
                            ['lang_code' => 'ar', 'label' => 'About Us'],
                        ],
                    ],
                    [
                        'link'         => '/privacy-policy',
                        'sort'         => 2,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Privacy Policy'],
                            ['lang_code' => 'ar', 'label' => 'سياسة الخصوصية'],
                        ],
                    ],
                    [
                        'link'         => '/terms-and-conditions',
                        'sort'         => 3,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Terms and Conditions'],
                            ['lang_code' => 'ar', 'label' => 'الشروط والأحكام'],
                        ],
                    ],
                    [
                        'link'         => '/return-policy',
                        'sort'         => 4,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Return Policy'],
                            ['lang_code' => 'ar', 'label' => 'سياسة الإرجاع'],
                        ],
                    ],
                    [
                        'link'         => '/faq',
                        'sort'         => 5,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'FAQ'],
                            ['lang_code' => 'ar', 'label' => 'الأسئلة الشائعة'],
                        ],
                    ],
                ],
            ],
            [
                'slug'         => DefaultMenusEnum::FOOTER_MENU_TWO->value,
                'translations' => [
                    ['lang_code' => 'en', 'name' => 'Footer Menu Two'],
                    ['lang_code' => 'ar', 'name' => 'القائمة السفلية الثانية'],
                ],
                'menu_items'   => [
                    [
                        'link'         => '/login',
                        'sort'         => 1,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Login'],
                            ['lang_code' => 'ar', 'label' => 'تسجيل الدخول'],
                        ],
                    ],
                    [
                        'link'         => '/contact-us',
                        'sort'         => 2,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Contact Us'],
                            ['lang_code' => 'ar', 'label' => 'Contact Us'],
                        ],
                    ],
                    [
                        'link'         => '/brands',
                        'sort'         => 3,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Brands'],
                            ['lang_code' => 'ar', 'label' => 'علامات تجارية'],
                        ],
                    ],
                    [
                        'link'         => '/flash-deals',
                        'sort'         => 4,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Flash Deals'],
                            ['lang_code' => 'ar', 'label' => 'عروض فلاش'],
                        ],
                    ],
                    [
                        'link'         => '/gift-cards',
                        'sort'         => 5,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Gift Cards'],
                            ['lang_code' => 'ar', 'label' => 'بطاقات الهدايا'],
                        ],
                    ],
                ],
            ],
            [
                'slug'         => DefaultMenusEnum::FOOTER_MENU_USER->value,
                'translations' => [
                    ['lang_code' => 'en', 'name' => 'Footer User Menu'],
                    ['lang_code' => 'ar', 'name' => 'القائمة السفلية الثانية'],
                ],
                'menu_items'   => [
                    [
                        'link'         => '/user/dashboard',
                        'sort'         => 1,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Dashboard'],
                            ['lang_code' => 'ar', 'label' => 'لوحة القيادة'],
                        ],
                    ],
                    [
                        'link'         => '/user/orders',
                        'sort'         => 2,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Orders'],
                            ['lang_code' => 'ar', 'label' => 'الطلبات'],
                        ],
                    ],
                    [
                        'link'         => '/track-order',
                        'sort'         => 3,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Track Order'],
                            ['lang_code' => 'ar', 'label' => 'تتبع الطلب'],
                        ],
                    ],
                    [
                        'link'         => '/user/wishlist',
                        'sort'         => 4,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Wish List'],
                            ['lang_code' => 'ar', 'label' => 'قائمة الرغبات'],
                        ],
                    ],
                    [
                        'link'         => '/user/change-password',
                        'sort'         => 5,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Change Password'],
                            ['lang_code' => 'ar', 'label' => 'تغيير كلمة المرور'],
                        ],
                    ],
                ],
            ],
            [
                'slug'         => DefaultMenusEnum::FOOTER_MENU_VENDOR->value,
                'translations' => [
                    ['lang_code' => 'en', 'name' => 'Footer Vendor Menu'],
                    ['lang_code' => 'ar', 'name' => 'القائمة السفلية الثانية'],
                ],
                'menu_items'   => [
                    [
                        'link'         => '/seller/dashboard',
                        'sort'         => 1,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Dashboard'],
                            ['lang_code' => 'ar', 'label' => 'لوحة القيادة'],
                        ],
                    ],
                    [
                        'link'         => '/seller/products',
                        'sort'         => 2,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Products'],
                            ['lang_code' => 'ar', 'label' => 'منتجات'],
                        ],
                    ],
                    [
                        'link'         => '/seller/orders',
                        'sort'         => 3,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Orders'],
                            ['lang_code' => 'ar', 'label' => 'الطلبات'],
                        ],
                    ],
                    [
                        'link'         => '/seller/shop-profile',
                        'sort'         => 4,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Shop Profile'],
                            ['lang_code' => 'ar', 'label' => 'ملف تعريف المتجر'],
                        ],
                    ],
                    [
                        'link'         => '/seller/my-withdraw',
                        'sort'         => 5,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'My Withdraw'],
                            ['lang_code' => 'ar', 'label' => 'سحب الأموال'],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($menu_list as $menu) {
            $data       = new Menu;
            $data->name = $menu['translations'][0]['name'];
            $data->slug = $menu['slug'];

            if ($data->save()) {
                foreach ($menu['translations'] as $translate) {
                    MenuTranslation::create([
                        'menu_id'   => $data->id,
                        'lang_code' => $translate['lang_code'],
                        'name'      => $translate['name'],
                    ]);
                }

                if (isset($menu['menu_items']) && is_array($menu['menu_items'])) {
                    processMenuItems($menu['menu_items'], $data->id, 0);
                }
            }
        }

        if ($vendorOne = Vendor::inRandomOrder()->first()) {
            MenuItem::where('label', 'Shop 1')->update([
                'link' => '/shops/' . $vendorOne->shop_slug,
            ]);
        }

        if ($vendorOne && $vendorTwo = Vendor::whereNotIn('id', [$vendorOne->id])->inRandomOrder()->first()) {
            MenuItem::where('label', 'Shop 2')->update([
                'link' => '/shops/' . $vendorTwo->shop_slug,
            ]);
        }
    }
}
