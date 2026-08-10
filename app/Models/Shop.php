<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shop) {
            if (empty($shop->reference_code)) {
                $shop->reference_code = self::generateReferenceCode();
            }
        });
    }

    /**
     * Generate a random unique reference code, e.g. "#shop-5671".
     */
    public static function generateReferenceCode(): string
    {
        do {
            $code = '#shop-' . random_int(1000, 9999);
        } while (self::where('reference_code', $code)->exists());

        return $code;
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * Technicians who registered using this shop's reference code.
     */
    public function registeredTechnicians()
    {
        return $this->hasMany(User::class, 'referral_shop_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public function photos()
    {
        return $this->hasMany(ShopPhoto::class);
    }

    public function jobs()
    {
        return $this->hasMany(StaffJob::class);
    }

    public function primaryPhoto()
    {
        return $this->hasOne(ShopPhoto::class)->where('is_primary', true);
    }

    public function getCategoryLabelAttribute()
    {
        $labels = [
            'electrician' => 'Electrician',
            'wifi_controller' => 'WiFi Installer',
            'solar' => 'Solar',
            'plumber' => 'Plumber',
        ];
        
        return $labels[$this->category] ?? ucfirst($this->category);
    }
}
