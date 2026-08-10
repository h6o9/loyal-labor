<?php

namespace App\Http\Traits;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

trait ResolvesSanctumApiUser
{
    protected function resolveSanctumUser(Request $request): ?User
    {
        $user = $request->user();

        if ($user instanceof User) {
            return $user;
        }

        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return null;
        }

        $accessToken = PersonalAccessToken::findToken($bearerToken);

        if (!$accessToken) {
            return null;
        }

        if ($accessToken->expires_at && $accessToken->expires_at->isPast()) {
            return null;
        }

        $tokenable = $accessToken->tokenable;

        return $tokenable instanceof User ? $tokenable : null;
    }
}
