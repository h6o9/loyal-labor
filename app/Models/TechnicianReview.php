<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicianReview extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tags' => 'array',
        'is_anonymous' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
