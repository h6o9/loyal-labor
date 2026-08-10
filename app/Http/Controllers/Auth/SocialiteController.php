<?php

namespace App\Http\Controllers\Auth;

use App\Enums\SocialiteDriverType;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\NewUserCreateTrait;
use App\Traits\SetConfigTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    use NewUserCreateTrait, SetConfigTrait;

    public function __construct()
    {
        $driver = request('driver', null);
        if ($driver == SocialiteDriverType::GOOGLE->value) {
            self::setGoogleLoginInfo();
        }
    }

    public function redirectToDriver($driver)
    {
        if (in_array($driver, SocialiteDriverType::getAll())) {
            return Socialite::driver($driver)->redirect();
        }
        $notification = __('Invalid Social Login Type!');
        $notification = ['message' => $notification, 'alert-type' => 'error'];

        return redirect()->back()->with($notification);
    }

    public function handleDriverCallback($driver)
    {
        if (! in_array($driver, SocialiteDriverType::getAll())) {
            $notification = __('Invalid Social Login Type!');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }
        try {
            $provider_name = SocialiteDriverType::from($driver)->value;
            $callbackUser = Socialite::driver($provider_name)->stateless()->user();
            $user = User::where('email', $callbackUser->getEmail())->first();
            if ($user) {
                $findDriver = $user
                    ->socialite()
                    ->where(['provider_name' => $provider_name, 'provider_id' => $callbackUser->getId()])
                    ->first();

                if ($findDriver) {
                    if ($user->status == UserStatus::ACTIVE->value) {
                        if ($user->is_banned == UserStatus::UNBANNED->value) {
                            if (app()->isProduction() && $user->email_verified_at == null) {
                                $notification = __('Please verify your email');
                                $notification = ['message' => $notification, 'alert-type' => 'error'];

                                return redirect()
                                    ->back()
                                    ->with($notification);
                            }
                            if ($findDriver) {
                                Auth::guard('web')->login($user, true);
                                $notification = __('Logged in successfully.');
                                $notification = ['message' => $notification, 'alert-type' => 'success'];

                                $intendedUrl = session()->get('url.intended');
                                if ($intendedUrl && Str::contains($intendedUrl, '/admin')) {
                                    return redirect()->route('dashboard');
                                }

                                return redirect()->intended(route('dashboard'))->with($notification);
                            }
                        } else {
                            $notification = __('Inactive account');
                            $notification = ['message' => $notification, 'alert-type' => 'error'];

                            return redirect()
                                ->back()
                                ->with($notification);
                        }
                    } else {
                        $notification = __('Inactive account');
                        $notification = ['message' => $notification, 'alert-type' => 'error'];

                        return redirect()
                            ->back()
                            ->with($notification);
                    }
                } else {
                    $socialite = $this->createNewUser(callbackUser: $callbackUser, provider_name: $provider_name, user: $user);

                    if ($socialite) {
                        Auth::guard('web')->login($user, true);
                        $notification = __('Logged in successfully.');
                        $notification = ['message' => $notification, 'alert-type' => 'success'];

                        $intendedUrl = session()->get('url.intended');
                        if ($intendedUrl && Str::contains($intendedUrl, '/admin')) {
                            return redirect()->route('dashboard');
                        }

                        return redirect()->intended(route('dashboard'))->with($notification);
                    }

                    $notification = __('Login Failed');
                    $notification = ['message' => $notification, 'alert-type' => 'error'];

                    return redirect()
                        ->back()
                        ->with($notification);
                }
            } else {
                if ($callbackUser) {
                    $socialite = $this->createNewUser(callbackUser: $callbackUser, provider_name: $provider_name, user: false);

                    if ($socialite) {
                        $user = User::find($socialite->user_id);
                        Auth::guard('web')->login($user, true);
                        $notification = __('Logged in successfully.');
                        $notification = ['message' => $notification, 'alert-type' => 'success'];

                        $intendedUrl = session()->get('url.intended');
                        if ($intendedUrl && Str::contains($intendedUrl, '/admin')) {
                            return redirect()->route('dashboard');
                        }

                        return redirect()->intended(route('dashboard'))->with($notification);
                    }

                    $notification = __('Login Failed');
                    $notification = ['message' => $notification, 'alert-type' => 'error'];

                    return redirect()
                        ->back()
                        ->with($notification);
                }

                $notification = __('Login Failed');
                $notification = ['message' => $notification, 'alert-type' => 'error'];

                return redirect()
                    ->back()
                    ->with($notification);
            }
        } catch (\Exception $e) {
            return to_route('login');
        }
    }
}
