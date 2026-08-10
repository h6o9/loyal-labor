<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $table = 'service_categories';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function technicians()
    {
        return $this->belongsToMany(
            User::class,
            'service_category_user',
            'service_category_id',
            'user_id'
        )->withTimestamps();
    }

    public function activeTechnicians()
    {
        return $this->technicians()
            ->where('users.user_type', 'technician')
            ->where('users.status', 'active');
    }
}
