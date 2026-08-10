<?php

namespace App\Http\Controllers\Staff\Auth;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;


class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function custom_reset_password_page(Request $request, $token)
    {

        $staff = Staff::select('id', 'name', 'email', 'forget_password_token')->where('forget_password_token', $token)->first();

        if (!$staff) {
            return view('auth.expired-token');
        }

        return view('staff.auth.reset-password', ['staff' => $staff, 'token' => $token]);
    }

    /**
     * Handle an incoming new password request.
     */
    public function custom_reset_password_store(Request $request, $token)
    {

        $setting = Cache::get('setting');

        $rules = [
            'email'    => 'required',
            'password' => 'required|min:4|confirmed',
        ];
        $customMessages = [
            'email.required'    => __('Email is required'),
            'password.required' => __('Password is required'),
            'password.min'      => __('Password must be 4 characters'),
        ];
        $this->validate($request, $rules, $customMessages);

        $staff = Staff::select('id', 'name', 'email', 'forget_password_token')->where('forget_password_token', $token)->where('email', $request->email)->first();

        if (!$staff) {
            return view('auth.expired-token');
        }

        $staff->password              = Hash::make($request->password);
        $staff->forget_password_token = null;
        $staff->save();

        $notification = __('Password Reset successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('staff.login')->with($notification);
    }
}
