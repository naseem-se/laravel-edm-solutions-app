<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            // Validate request data
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            $admin = Admin::whereEmail($request->email)->first();
            if (!$admin) {
                return redirect()->back()->with('error', 'Invalid email or password');
            }

            // Authenticate user
            if (auth()->guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('admin.dashboard')->with('success', 'Login successfully');
            } else {
                return back()->with('error', 'Invalid email or password.');
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function logout()
    {
        auth()->guard('admin')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('pages.login')->with('success', 'Logout Successfully');
    }
}
