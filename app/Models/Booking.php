<?php

namespace App\Models;

use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'service_date' => 'date',
        'time_slot' => 'datetime:H:i',
        'accepted_at' => 'datetime',
        'on_the_way_at' => 'datetime',
        'work_started_at' => 'datetime',
        'work_in_progress_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'last_expand_prompt_at' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
        'completion_otp_expires_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function isBroadcast(): bool
    {
        return ($this->booking_type ?? 'direct') === 'broadcast';
    }
}