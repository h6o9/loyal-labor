<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Shipping\app\Models\ShippingRule;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'country_id',
        'state_id',
        'city_id',
        'zip_code',
        'address',
        'walk_in_customer',
        'type',
        'default',
        'status',
    ];

    protected $appends = ['full_address'];

    public function getFullAddressAttribute()
    {
        return $this->address.', '.$this->city->name.', '.$this->state->name.', '.$this->zip_code.', '.$this->country->name;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function getShippingRulesAttribute()
    {
        $address = $this;
        $shipping = ShippingRule::with('items')
            ->where('status', 1)
            ->whereHas('items', function ($q) use ($address) {
                $q->where(function ($query) use ($address) {
                    $query->where(function ($q) use ($address) {
                        // Check if city_id exists in shipping_rule_items
                        $q->whereNotNull('city_id')
                            ->whereJsonContains('city_id', (string) $address->city_id);
                    })
                        ->orWhere(function ($q) use ($address) {
                            // If city_id is NULL, check state_id
                            $q->whereNull('city_id')
                                ->whereNotNull('state_id')
                                ->whereJsonContains('state_id', (string) $address->state_id);
                        })
                        ->orWhere(function ($q) use ($address) {
                            // If both city_id and state_id are NULL, check country_id
                            $q->whereNull('city_id')
                                ->whereNull('state_id')
                                ->whereNotNull('country_id')
                                ->whereJsonContains('country_id', (string) $address->country_id);
                        });
                });
            })
            ->get();

        return $shipping;
    }

    public function getShippingChargeAttribute($range = [], $price = 0, $weight = 0)
    {
        // check shipping rule

        // check if shippingRule has from amount and
    }
}
