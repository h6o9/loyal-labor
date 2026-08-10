<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffJob extends Model
{
    protected $guarded = [];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime:H:i:s',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(Admin::class, 'assigned_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(Staff::class, 'assigned_to');
    }

    // Available job types
    public static $jobTypes = [
        'visit' => 'Shop Visit',
        'repair' => 'Repair Service',
        'install' => 'Installation',
        'maintenance' => 'Maintenance',
        'inspection' => 'Inspection',
        'follow_up' => 'Follow Up',
    ];
}
