<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $guarded = [];

    protected $casts = [
        'payload' => 'array',
        'target_user_ids' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }
}
