<?php

namespace Modules\Product\app\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Route;

class WebsiteProductScope implements Scope
{
    /**
     * @param Builder $builder
     * @param Model   $model
     */
    public function apply(Builder $builder, Model $model): void
    {
        $routeName = Route::currentRouteName();

        $isWebsite       = str_starts_with($routeName, 'website.');
        $isDemo          = strtolower(config('app.app_mode')) === 'demo';
        $isAllHomeActive = getSettings('show_all_homepage') == 1;
        $theme           = config('services.theme') ?? 1;

        if ($isWebsite && ($isDemo || $isAllHomeActive) && $theme) {
            $builder->where('theme', $theme);
        }
    }
}
