<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Models\Document;
use Exception;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:100',
                'zip_code' => 'required|string|max:20',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'department' => 'required|string|max:255',
                'job_title' => 'required|string|max:255',
                'specialities' => 'required|array',
                'specialities.*' => 'string|max:255',
            ]);

            $user = auth()->user();

            // Default to existing image
            $filename = $user->image;

            if ($request->hasFile('image')) {
                // Delete old image safely
                if ($user->image && Storage::disk('public')->exists($user->image)) {
                    Storage::disk('public')->delete($user->image);
                }

                // Store new image
                $filename = $request->file('image')->store('profile', 'public');
            }

            $user->update([
                'address' => $validated['address'],
                'city' => $validated['city'],
                'zip_code' => $validated['zip_code'],
                'image' => $filename,
                'department' => $validated['department'],
                'job_title' => $validated['job_title'],
                'specialities' => $validated['specialities'], // JSON cast recommended
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $user->fresh(),
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = auth()->user();
            if (!Hash::check($validated['old_password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Old password is incorrect'
                ]);
            }
            $user->update([
                'password' => bcrypt($validated['password'])
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getBankAccount()
    {
        try {
            $user = auth()->user();
            $bankAccount = $user->bankAccount;
            return response()->json([
                'success' => true,
                'data' => $bankAccount
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function addBankAccount(Request $request)
    {
        try {
            $user = auth()->user();
            $bankAccount = $user->bankAccount;
            if ($bankAccount) {
                $bankAccount->update([
                    'bank_name' => $request->bank_name,
                    'account_holder_name' => $request->account_holder_name,
                    'account_number' => $request->account_number,
                    'routing_number' => $request->routing_number
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Bank update successfully'
                ]);
            }
            BankAccount::create([
                'user_id' => $user->id,
                'bank_name' => $request->bank_name,
                'account_holder_name' => $request->account_holder_name,
                'account_number' => $request->account_number,
                'routing_number' => $request->routing_number
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bank account added successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getWeeklySummary()
    {
        $userId = auth()->id();

        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        // Daily Entries
        $entries = DB::table('claim_shifts')
            ->selectRaw("
            DATE(created_at) as entry_date,
            DAYNAME(created_at) as day_name,
            ROUND(TIMESTAMPDIFF(MINUTE, start_time, end_time) / 60, 2) as hours
        ")
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->orderBy('created_at')
            ->get()
            ->map(function ($item) {
                return [
                    'day' => $item->day_name,
                    'date' => \Carbon\Carbon::parse($item->entry_date)->format('M d'),
                    'hours' => (float) $item->hours,
                ];
            });

        // Weekly Totals
        $weekly = DB::table('claim_shifts')
            ->selectRaw("
            SUM(TIMESTAMPDIFF(MINUTE, start_time, end_time)) / 60 as total_hours
        ")
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->first();

        $totalHours = round($weekly->total_hours ?? 0, 2);
        $regularHours = min($totalHours, 36);
        $overtimeHours = $totalHours > 36 ? $totalHours - 36 : 0;

        return response()->json([
            'week' => $weekStart->format('M d') . '-' . $weekEnd->format('d'),
            'status' => 'Pending',
            'total_hours' => $totalHours,
            'regular_hours' => $regularHours,
            'overtime' => $overtimeHours,
            'days' => $entries
        ]);
    }

    public function uploadComplianceDocument(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:100',
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $userId = auth()->id();

            // Check if document already exists for this user + type
            $existing = Document::where('user_id', $userId)
                ->where('type', $request->type)
                ->first();

            // Upload new file
            $path = $request->file('document')->store('documents', 'public');

            if ($existing) {

                // Delete old file if exists
                if ($existing->document && Storage::disk('public')->exists($existing->document)) {
                    Storage::disk('public')->delete($existing->document);
                }

                // Update existing document
                $existing->update([
                    'document' => $path,
                    'status' => 0,   // reset status if required
                ]);

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Document updated successfully.',
                    'data' => $existing,
                ], 200);

            } else {

                // Create new document
                $document = Document::create([
                    'user_id' => $userId,
                    'document' => $path,
                    'type' => $request->type,
                    'status' => 0,
                ]);

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Document uploaded successfully.',
                    'data' => $document,
                ], 201);
            }

        } catch (Exception $th) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }



}
