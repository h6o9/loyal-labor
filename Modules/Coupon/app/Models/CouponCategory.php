<?php

namespace Modules\Coupon\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\app\Models\Category;

class CouponCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'coupon_id',
        'category_id',
        'exclude',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
