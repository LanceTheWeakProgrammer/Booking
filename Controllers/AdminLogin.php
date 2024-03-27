<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminLogin extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'adminUsername' => 'required',
            'adminPassword' => 'required',
        ]);

        $adminUsername = $request->input('adminUsername');
        $adminPassword = $request->input('adminPassword');

        $admin = AdminInfo::where('adminUsername', $adminUsername)->first();

        if ($admin && Hash::check($adminPassword, $admin->adminPassword)) {
            Auth::guard('admin')->loginUsingId($admin->adminID);
            return redirect()->route('admin.dashboard')->with('success', 'Login successful!');
        } else {
            return redirect()->back()->with('error', 'Login failed - Wrong Credentials!');
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect()->route('admin.login')->with('success', 'Logout successful!');
    }
}
