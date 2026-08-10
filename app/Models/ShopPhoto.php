<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopPhoto extends Model
{
    protected $table = 'shop_photos';
    
    protected $fillable = [
        'shop_id',
        'photo_path',
        'is_primary'
    ];
    
    protected $casts = [
        'is_primary' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // Relationships
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
    
    // Accessor for full photo URL
    public function getPhotoUrlAttribute(): string
    {
        return asset('storage/' . $this->photo_path);
    }
    
    // Boot method to handle primary photo logic
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($photo) {
            // If this is the first photo for the shop, make it primary
            if ($photo->shop->photos()->count() === 0) {
                $photo->is_primary = true;
            }
        });
        
        static::deleting(function ($photo) {
            // If deleting primary photo, make another photo primary
            if ($photo->is_primary) {
                $nextPhoto = $photo->shop->photos()->where('id', '!=', $photo->id)->first();
                if ($nextPhoto) {
                    $nextPhoto->update(['is_primary' => true]);
                }
            }
        });
    }
}