<?php

namespace App\Http\Controllers\Staff\Auth;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StaffAuthenticatedSessionController extends Controller
{
    //
	

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('staff.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
public function store(Request $request)
{ 
	$rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    $customMessages = [
        'email.required' => __('Email is required'),
        // 'password.required' => __('Password is required'),
    ];

    $request->validate($rules, $customMessages);

    $credentials = $request->only('email','password');

    $staff = Staff::where('email', $request->email)->first();
	 // Debugging line to check if staff is retrieved correctl

    if (!$staff) {
        return back()->with([
            'message' => __('Invalid Email'),
            'alert-type' => 'error'
        ]);
    }

    if ($staff->status != 1) {
        return back()->with([
            'message' => __('Inactive account'),
            'alert-type' => 'error'
        ]);
    }

    if (Auth::guard('staff')->attempt($credentials, $request->remember)) {
        $request->session()->regenerate();
        
        // Set session variables for better tracking
        session(['staff_authenticated' => true]);
        session(['staff_login_time' => now()]);
        session(['staff_last_activity' => now()]);

        return redirect()->route('staff.dashboard')->with([
            'message' => __('Logged in successfully.'),
            'alert-type' => 'success'
        ]);
    }

    return back()->with([
        'message' => __('Invalid Password'),
        'alert-type' => 'error'
    ]);
}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Clear session variables
        session()->forget(['staff_authenticated', 'staff_login_time', 'staff_last_activity']);
        
        Auth::guard('staff')->logout();

        $notification = __('Logged out successfully.');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('staff.login')->with($notification);
    }
}
