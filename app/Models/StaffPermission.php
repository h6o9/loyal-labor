<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffPermission extends Model
{
    protected $guarded = [];

    protected $casts = [
        'can_view' => 'boolean',
        'can_create' => 'boolean',
        'can_edit' => 'boolean',
        'can_delete' => 'boolean',
        'permissable' => 'boolean',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    // Available modules
    public static $modules = [
        'shop_management' => 'Shop Management',
    ];
}
