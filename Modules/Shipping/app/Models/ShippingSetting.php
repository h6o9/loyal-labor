<?php

namespace Modules\Shipping\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'hide_other_shipping',
        'hide_shipping_option',
        'sort_shipping_direction',
    ];
}
