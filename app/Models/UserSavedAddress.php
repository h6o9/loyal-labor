<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSavedAddress extends Model
{
    protected $table = 'user_saved_addresses';

    protected $fillable = [
        'user_id',
        'label',
        'address',
        'city',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label ?: 'Home',
            'type' => $this->label ?: 'Home',
            'address' => $this->address,
            'city' => $this->city,
            'is_default' => (bool) $this->is_default,
        ];
    }
}
