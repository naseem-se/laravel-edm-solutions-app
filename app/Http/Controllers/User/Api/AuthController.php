<?php

namespace App\Http\Controllers\User\Api;

use App\Events\UserEmailVerification;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Http\Requests\EmailVerificationRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResendOtpRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();
            DB::beginTransaction();

            $code = rand(1000, 9999);

            $user = User::create([
                'role' => $validated['role'],
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'password' => bcrypt($validated['password']),
                'code' => $code,
            ]);

            activity()
            ->causedBy($user)
            ->inLog('register')
            ->withProperties(['ip' => request()->ip()])
            ->log('User Registered.');


            // 🔥 Fire event after successful registration
            event(new UserEmailVerification($user));
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => ['Registered successfully. Please check your email to verify your account.'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => [$th->getMessage()],
            ]);
        }
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        try {
            $validated = $request->validated();
            DB::beginTransaction();
            $user = User::where('code', $validated['code'])->first();
            if (!$user) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => ['Invalid code.'],
                ]);
            }
            $user->update([
                'is_verified' => true,
                'code' => 0
            ]);
            activity()
            ->causedBy($user)
            ->inLog('verfification')
            ->withProperties(['ip' => request()->ip()])
            ->log('User Email Verfiy.');

            DB::commit();
            if ($request['forget_password']) {
                $token = $user->createToken('forget_password')->plainTextToken;
                return response()->json([
                    'success' => true,
                    'message' => ['Email verified successfully.'],
                    'token' => $token,
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => ['Email verified successfully.'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => [$th->getMessage()],
            ]);
        }
    }

    public function resendOtp(ResendOtpRequest $request)
    {
        try {
            $validated = $request->validated();
            DB::beginTransaction();
            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => ['User not found'],
                ]);
            }
            $code = rand(1000, 9999);
            $user->update([
                'code' => $code
            ]);
            event(new UserEmailVerification($user));

            activity()
            ->causedBy($user)
            ->inLog('resend_otp')
            ->withProperties(['ip' => request()->ip()])
            ->log('User requested for resend OTP.');
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => ['Check your mail, Email sended successfully.'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => [$th->getMessage()],
            ]);
        }
    }

    public function forgetPassword(ResendOtpRequest $request)
    {
        try {
            $validated = $request->validated();
            DB::beginTransaction();
            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => ['User not found'],
                ]);
            }
            $code = rand(1000, 9999);
            $user->update([
                'code' => $code
            ]);
            event(new UserEmailVerification($user));

            activity()
            ->causedBy($user)
            ->inLog('forgot_passord')
            ->withProperties(['ip' => request()->ip()])
            ->log('Forgot Password Request.');

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => ['Check your mail, Email sended successfully.'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => [$th->getMessage()],
            ]);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $validated = $request->validated();
            DB::beginTransaction();
            $user = auth()->user();
            $user->update([
                'password' => bcrypt($validated['password'])
            ]);

            activity()
            ->causedBy($user)
            ->inLog('password_reset')
            ->withProperties(['ip' => request()->ip()])
            ->log('User reset password.');

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => ['Password reset successfully.'],
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => [$th->getMessage()],
            ]);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => ['Email or password is incorrect'],
                ]);
            }
            if (!Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => ['Email or password is incorrect'],
                ]);
            }
            if (!$user->is_verified) {
                return response()->json([
                    'success' => false,
                    'message' => ['Email not verified'],
                ]);
            }

            if ($user->role != $validated['role']) {
                return response()->json([
                    'success' => false,
                    'message' => ['Your account is not registered to this ' . $validated['role']]
                ]);
            }
            

            $token = $user->createToken('auth_token')->plainTextToken;

            activity()
            ->causedBy($user)
            ->inLog('login')
            ->withProperties(['ip' => request()->ip()])
            ->log('User logged in');
            return response()->json([
                'success' => true,
                'message' => ['Login successfully.'],
                'token' => $token,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => [$th->getMessage()]
            ]);
        }
    }

    public function uploadDocument(DocumentRequest $request)
    {
        try {
            $validated = $request->validated();
            DB::beginTransaction();
            $user = auth()->user();
            $user->documents()->create([
                'document' => $request->file('document')->store('public', 'documents'),
                'type' => $validated['type']
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => ['Document uploaded successfully.'],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => [$th->getMessage()]
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => ['User not found.'],
                ]);
            }

            activity()
            ->causedBy(auth()->user())
            ->inLog('logout')
            ->withProperties(['ip' => request()->ip()])
            ->log('User logged out');

            $user->tokens()->delete();
            return response()->json([
                'success' => true,
                'message' => ['Logged out successfully.'],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => [$th->getMessage()],
            ]);
        }
    }

}
