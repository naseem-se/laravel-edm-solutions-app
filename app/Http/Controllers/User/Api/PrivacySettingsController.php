<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\UserEmailVerification;

class PrivacySettingsController extends Controller
{
    public function getSettings()
    {
        $user = auth()->user();

        return response()->json([
            'success' => true,
            'data' => [
                'profile_visibility' => (bool) $user->profile_visibility,
                'two_factor_enabled' => (bool) $user->two_factor_enabled,
                'biometric_lock' => (bool) $user->biometric_lock,
            ],
        ], 200);
    }

    public function updateProfileVisibility(Request $request)
    {
        $request->validate([
            'visibility' => 'required|boolean',
        ]);

        $user = auth()->user();
        $user->update(['profile_visibility' => $request->visibility]);

        return response()->json([
            'success' => true,
            'message' => $request->visibility ? 'Profile is now visible' : 'Profile is now hidden',
            'data' => ['profile_visibility' => (bool) $user->profile_visibility],
        ], 200);
    }

    public function enableTwoFactor(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $user = auth()->user();
        $code = rand(100000, 999999);

        $user->update([
            'two_factor_enabled' => true,
            'two_factor_code' => $code,
        ]);

        event(new UserEmailVerification($user));

        return response()->json([
            'success' => true,
            'message' => 'Two-factor authentication enabled. Check your phone for code.',
            'data' => ['two_factor_enabled' => true],
        ], 200);
    }

    public function verifyTwoFactorCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = auth()->user();

        if ($user->two_factor_code !== $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid code',
            ], 400);
        }

        $user->update(['two_factor_code' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Two-factor code verified successfully',
        ], 200);
    }

    public function disableTwoFactor()
    {
        $user = auth()->user();
        $user->update([
            'two_factor_enabled' => false,
            'two_factor_code' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Two-factor authentication disabled',
            'data' => ['two_factor_enabled' => false],
        ], 200);
    }

    public function updateBiometricLock(Request $request)
    {
        $request->validate([
            'enabled' => 'required|boolean',
        ]);

        $user = auth()->user();
        $user->update(['biometric_lock' => $request->enabled]);

        return response()->json([
            'success' => true,
            'message' => $request->enabled ? 'Biometric lock enabled' : 'Biometric lock disabled',
            'data' => ['biometric_lock' => (bool) $user->biometric_lock],
        ], 200);
    }
}
