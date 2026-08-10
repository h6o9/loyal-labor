<?php

namespace Modules\Shipping\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'type',
        'currency_id',
        'from',
        'to',
        'price',
        'status',
    ];

    public function items()
    {
        return $this->hasOne(ShippingRuleItem::class, 'shipping_rule_id');
    }
}
