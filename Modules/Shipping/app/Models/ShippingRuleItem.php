<?php

namespace Modules\Shipping\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRuleItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'shipping_rule_id',
        'country_id',
        'state_id',
        'city_id',
        'zip_code',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'country_id' => 'array',
        'state_id' => 'array',
        'city_id' => 'array',
    ];

    public function shippingRule()
    {
        return $this->belongsTo(ShippingRule::class, 'shipping_rule_id');
    }
}
