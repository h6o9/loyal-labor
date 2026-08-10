<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\CustomRecaptcha;
use App\Services\MailSenderService;
use App\Traits\GetGlobalInformationTrait;
use App\Traits\GlobalMailTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\AntiBotFormRequest;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    use GetGlobalInformationTrait, GlobalMailTrait;

    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @return mixed
     */
    public function store(AntiBotFormRequest $request): RedirectResponse
    {
        $setting = Cache::get('setting');

        $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password'        => ['required', 'confirmed', 'min:4', 'max:100'],
            'recaptcha_token' => $setting->recaptcha_status == 'active' ? ['required', new CustomRecaptcha] : '',
            'tos'             => ['required', 'accepted'],
        ], [
            'name.required'            => __('Name is required'),
            'email.required'           => __('Email is required'),
            'email.unique'             => __('Email already exist'),
            'password.required'        => __('Password is required'),
            'password.confirmed'       => __('Confirm password does not match'),
            'password.min'             => __('You have to provide minimum 4 character password'),
            'recaptcha_token.required' => __('Please complete the recaptcha to submit the form'),
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name'               => $request->name,
                'email'              => $request->email,
                'password'           => Hash::make($request->password),
                'verification_token' => Str::random(100),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->handleMailException($e);
        }

        if (isset($user) && $user) {
            (new MailSenderService)->sendVerifyMailSingleUser($user);
        }

        $notification = __('A verification link has been sent to your mail, please verify and enjoy our service');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    /**
     * @param $token
     */
    public function custom_user_verification($token)
    {
        $user = User::where('verification_token', $token)->first();

        if ($user) {
            if (filled($user->email_verified_at)) {
                return to_route('login')->with(['message' => __('Email already verified'), 'alert-type' => 'success']);
            }

            $user->email_verified_at  = now();
            $user->verification_token = null;
            $user->save();

            return to_route('login')->with(['message' => __('Email verified successfully, you can now login'), 'alert-type' => 'success']);
        } else {
            return to_route('register')->with(['message' => __('Invalid token'), 'alert-type' => 'error']);
        }
    }
}
