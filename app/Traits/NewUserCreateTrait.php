<?php

namespace App\Traits;

use App\Enums\UserStatus;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait NewUserCreateTrait
{
    use GlobalMailTrait;

    private function createNewUser($callbackUser, $provider_name, $user)
    {
        if (! $user) {
            $password = Str::random(10);
            $user = User::create([
                'name' => $callbackUser->name,
                'email' => $callbackUser->email,
                'status' => UserStatus::ACTIVE->value,
                'is_banned' => UserStatus::UNBANNED->value,
                'email_verified_at' => now(),
                'password' => Hash::make($password),
            ]);
            try {
                [$subject, $message] = $this->fetchEmailTemplate('social_login', ['user_name' => $user->name, 'app_name' => config('app.name'), 'password' => $password]);
                $this->sendMail($user->email, $subject, $message);
            } catch (Exception $e) {
                session(['error' => $e->getMessage()]);
                Log::error($e);
            }
        }

        $socialite = $user->socialite()->create([
            'provider_name' => $provider_name,
            'provider_id' => $callbackUser->getId(),
            'access_token' => $callbackUser->token ?? null,
            'refresh_token' => $callbackUser->refreshToken ?? null,
        ]);

        return $socialite;
    }
}
