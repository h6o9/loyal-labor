<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\CustomRecaptcha;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);

        $credential = $this->getCredentials($request);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            return $this->handleUserAuthentication($user, $credential, $request);
        } else {
            return $this->sendFailedLoginResponse(__('Invalid Email'));
        }
    }

    /**
     * @param Request $request
     */
    protected function validateRequest(Request $request)
    {
        $setting = Cache::get('setting');

        $rules = [
            'email'           => 'required|email',
            'password'        => 'required',
            'recaptcha_token' => $setting->recaptcha_status == 'active' ? ['required', new CustomRecaptcha()] : '',
        ];

        $customMessages = [
            'email.required'           => __('Email is required'),
            'password.required'        => __('Password is required'),
            'recaptcha_token.required' => __('Please complete the recaptcha to submit the form'),
        ];

        $this->validate($request, $rules, $customMessages);
    }

    /**
     * @param Request $request
     */
    protected function getCredentials(Request $request)
    {
        return [
            'email'    => $request->email,
            'password' => $request->password,
        ];
    }

    /**
     * @return mixed
     */
    protected function handleUserAuthentication($user, $credential, $request)
    {
        if ($user->status != UserStatus::ACTIVE->value) {
            return $this->sendFailedLoginResponse(__('Inactive account'));
        }

        if ($user->is_banned != UserStatus::UNBANNED->value) {
            return $this->sendFailedLoginResponse(__('Inactive account'));
        }

        if ($user->email_verified_at == null) {
            return $this->sendFailedLoginResponse(__('Please verify your email'));
        }

        if (!Hash::check($request->password, $user->password)) {
            return $this->sendFailedLoginResponse(__('Invalid Password'));
        }

        return $this->attemptLogin($credential, $request);
    }

    /**
     * @param $credential
     * @param $request
     */
    protected function attemptLogin($credential, $request)
    {
        if (Auth::guard('web')->attempt($credential, $request->remember)) {
            $notification = __('Logged in successfully.');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            $redirect = auth()->user()?->seller ? 'seller.dashboard' : 'website.user.dashboard';

            if (request()->r != 'login') {
                return back()->with($notification);
            }

            $intendedUrl = session()->get('url.intended');

            if ($intendedUrl && Str::contains($intendedUrl, '/admin')) {
                return redirect()->route($redirect);
            }

            return redirect()->intended(route($redirect))->with($notification);
        }
    }

    /**
     * @param $message
     */
    protected function sendFailedLoginResponse($message)
    {
        $notification = ['message' => $message, 'alert-type' => 'error'];

        return redirect()->back()->with($notification);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(): RedirectResponse
    {
        Auth::guard('web')->logout();

        $notification = __('Logged out successfully.');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('login')->with($notification);
    }
}
