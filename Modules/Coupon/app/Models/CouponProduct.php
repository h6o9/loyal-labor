<?php

namespace Modules\Coupon\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\app\Models\Product;

class CouponProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'coupon_id',
        'product_id',
        'exclude',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
