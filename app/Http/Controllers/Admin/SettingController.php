<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\PlatformConfig;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('admin')->user();
        return view('pages.admin.setting', compact('user'));
    }
    public function updateProfile(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {

            // Delete old photo if exists
            if ($admin->image && Storage::disk('public')->exists($admin->image)) {
                Storage::disk('public')->delete($admin->image);
            }

            // Upload new photo
            $path = $request->file('photo')->store('admin/photos', 'public');
            $admin->image = $path;
        }

        $admin->full_name = $request->full_name;
        $admin->email = $request->email;
        $admin->phone_number = $request->phone;
        $admin->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
            'two_factor' => ['nullable', 'boolean'],
        ]);

        // Check current password
        if (!\Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        // Update password
        $admin->password = \Hash::make($request->new_password);

        // Update two-factor
        $admin->two_factor = $request->has('two_factor') ? 1 : 0;

        $admin->save();

        return back()->with('success', 'Password updated successfully!');
    }

    public function roles()
    {
        return view('pages.admin.role-permissins');
    }
    public function platformConfigs()
    {
        $settings = PlatformConfig::first();
        return view('pages.admin.platfrom-configs', compact('settings'));
    }

    public function updatePlatformConfig(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'commission_percentage' => 'required|numeric|min:0|max:100',
            'cancellation_policy' => 'required|numeric|min:0',
            'payment_cycle' => 'nullable|string|in:hourly,daily,weekly,monthly,semi-annually,annually',
            'auto_approve_facility' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return back()->with('error','Validation fialed. Please try again.');
        }



        $settings = PlatformConfig::first() ?? new PlatformConfig();

        $settings->commission_percentage = $request->commission_percentage;
        $settings->cancellation_policy = $request->cancellation_policy;
        $settings->payment_cycle = $request->payment_cycle;
        $settings->auto_approve_facility = $request->has('auto_approve_facility');

        $settings->save();

        return back()->with('success', 'Platform configurations updated successfully!');
    }

    public function contentManagement()
    {
        $content = Content::first();
        return view('pages.admin.content-management', compact('content'));
    }

    public function getContent() {
        $content = Content::first() ?? new Content();
        return response()->json(['data' => $content]);
    }

    public function updateContent(Request $request) {
        $content = Content::first() ?? new Content();
        $content->fill($request->all());
        $content->save();
        
        return response()->json(['success' => true]);
    }


}
