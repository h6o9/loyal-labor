<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Staff extends Authenticatable
{
    use HasRoles, Notifiable;

    protected $guard_name = 'staff';

    protected $table = 'staff';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function staffPermissions()
    {
        return $this->hasMany(StaffPermission::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function assignedJobs()
    {
        return $this->hasMany(StaffJob::class, 'assigned_to');
    }

    public function createdShops()
    {
        return $this->hasMany(Shop::class, 'staff_id');
    }

    public function hasPermission($module, $action = 'can_view')
    {
        $permission = $this->staffPermissions()->where('module', $module)->first();
        return $permission && $permission->$action;
    }
}