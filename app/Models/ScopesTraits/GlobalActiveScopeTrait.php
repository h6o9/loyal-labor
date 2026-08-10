<?php

declare(strict_types=1);

namespace App\Models\ScopesTraits;

use Illuminate\Database\Eloquent\Builder;

trait GlobalActiveScopeTrait
{
    public function scopeActive(Builder $query): void
    {
        $query->where('status', 1);
    }

    public function scopeInactive(Builder $query): void
    {
        $query->where('status', 0);
    }

    public function scopeOfStatus(Builder $query, string|int $status): void
    {
        $query->where('status', $status);
    }
}
