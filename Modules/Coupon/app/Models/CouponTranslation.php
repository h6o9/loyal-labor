<?php

namespace Modules\Coupon\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'coupon_id',
        'name',
        'lang_code',
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
