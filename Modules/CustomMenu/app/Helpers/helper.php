<?php

use Modules\CustomMenu\app\Models\Menu;
use Modules\CustomMenu\app\Models\MenuItem;

if (! function_exists('mainMenu')) {
    function mainMenu($slug)
    {
        return menuGetBySlug($slug);
    }
}

if (! function_exists('menuGetBySlug')) {
    function menuGetBySlug($slug)
    {
        $menu = Menu::bySlug($slug);

        return is_null($menu) ? [] : menuGetById($menu->id);
    }
}

if (! function_exists('menuGetById')) {
    function menuGetById($menu_id)
    {
        $menuItem = new MenuItem;
        $menu_list = $menuItem->getAll($menu_id);

        $roots = $menu_list->where('menu_id', (int) $menu_id)->where('parent_id', 0);

        $items = menuTree($roots, $menu_list);

        return $items;
    }
}

if (! function_exists('menuTree')) {
    function menuTree($items, $all_items)
    {
        $data_arr = [];
        $i = 0;
        foreach ($items as $item) {
            $data_arr[$i] = $item->toArray();
            $find = $all_items->where('parent_id', $item->id);

            $data_arr[$i]['child'] = [];

            if ($find->count()) {
                $data_arr[$i]['child'] = menuTree($find, $all_items);
            }

            $i++;
        }

        return $data_arr;
    }
}
