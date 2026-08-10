<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
        protected $table = 'shop_categories';

        protected $guarded = [];

        public function shops()
        {
            return $this->hasMany(\App\Models\Shop::class, 'category_id');
        }
}
