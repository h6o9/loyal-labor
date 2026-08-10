<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function dashboard(Request $request)
    {
        // remove intended url from session
        $request->session()->forget('url');

        // Basic stats without module dependencies
        $stats = [
            'total_shops' => DB::table('shops')->count(),
            'total_users' => DB::table('users')->count(),
            'total_staff' => DB::table('staff')->count(),
            'total_admins' => DB::table('admins')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
