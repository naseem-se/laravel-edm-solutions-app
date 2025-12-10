<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClaimShiftRequest;
use App\Http\Resources\ShiftResource;
use App\Models\ClaimShift;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{

    public function shifts(Request $request)
    {
        try {
            // If date provided (e.g. ?date=2025-11-10) → use it, else today
            $date = $request->input('date')
                ? Carbon::parse($request->input('date'))->toDateString()
                : Carbon::today()->toDateString();


            // Fetch shifts for the given date
            $shifts = Shift::where('status', 1)
                ->whereDate('date', $date) // assuming your shifts table has 'date' column
                ->with('claimShift')
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'date' => $date,
                'data' => ShiftResource::collection($shifts),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function shiftDetails($id)
    {
        try {
            $shift = Shift::where('status', 1)->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => new ShiftResource($shift)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function claimShift(ClaimShiftRequest $request)
    {
        try {
            $validated = $request->validated();
            $shift = Shift::where('status', 1)->findOrFail($validated['shift_id']);
            $user = auth()->user();

            $today = now()->startOfDay();
            $shiftDate = Carbon::parse($shift->date)->startOfDay();

            // ❌ Don't allow claiming past shifts
            if ($shiftDate->lt($today)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot claim a past shift.',
                ]);
            }

            // ❌ Check if user already claimed this exact shift
            if ($shift->claimShift()->where('user_id', $user->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already claimed this shift.',
                ]);
            }

            // ❌ Check for overlapping claimed shifts — based on the related shift date
            $hasConflict = $user->claimShifts()
                ->whereHas('shift', function ($q) use ($shift) {
                    $q->whereDate('date', $shift->date);
                })
                ->where(function ($query) use ($shift) {
                    $query->whereBetween('start_time', [$shift->start_time, $shift->end_time])
                        ->orWhereBetween('end_time', [$shift->start_time, $shift->end_time])
                        ->orWhere(function ($q) use ($shift) {
                            $q->where('start_time', '<=', $shift->start_time)
                                ->where('end_time', '>=', $shift->end_time);
                        });
                })
                ->exists();

            if ($hasConflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a shift that overlaps with this time range on the same date.',
                ]);
            }

            // ✅ Otherwise, allow claiming
            $shift->claimShift()->create([
                'user_id' => $user->id,
                'start_time' => $shift->start_time,
                'end_time' => $shift->end_time,
            ]);

            $shift->update([
                'status' => 2, // Confirmed
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Shift claimed successfully!',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function getClaimedShift()
    {
        try {
            $user = auth()->user();
            $claimed_shift = $user->claimShifts()
                ->with('shift') // if claimShift belongsTo Shift
                ->latest()
                ->first();

            if (!$claimed_shift) {
                return response()->json([
                    'success' => false,
                    'message' => 'No claimed shift found.',
                ]);
            }

            $now = Carbon::now();

            // Combine date + time if shift date exists
            $shiftDate = $claimed_shift->shift?->date ?? Carbon::today()->toDateString();
            $start = Carbon::parse("{$shiftDate} {$claimed_shift->start_time}");
            $end = Carbon::parse("{$shiftDate} {$claimed_shift->end_time}");
            $check_in_enabled = false;
            $check_out_enabled = false;



            if ($claimed_shift->check_in == '00:00:00') {
                if ($now->greaterThanOrEqualTo($start)) {
                    $check_in_enabled = true;
                }
            } else {
                // Check-out logic
                if ($claimed_shift->check_out == '00:00:00') {
                    if ($now->greaterThanOrEqualTo($end)) {
                        $check_out_enabled = true;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $claimed_shift->id,
                    'shift_id' => $claimed_shift->shift_id,
                    'user_id' => $claimed_shift->user_id,
                    'start_time' => Carbon::parse($claimed_shift->start_time)->format('g:i A'),
                    'end_time' => Carbon::parse($claimed_shift->end_time)->format('g:i A'),
                    'check_in' =>  $claimed_shift->check_in == '00:00:00' ? '00:00:00' : Carbon::parse($claimed_shift->check_in)->format('g:i A'),
                    'check_out' => $claimed_shift->check_out == '00:00:00' ? '00:00:00' : Carbon::parse($claimed_shift->check_out)->format('g:i A'),
                    'check_in_enabled' => $check_in_enabled,
                    'check_out_enabled' => $check_out_enabled,
                    'location' => $claimed_shift->location,
                    'created_at' => $claimed_shift->created_at,
                    'updated_at' => $claimed_shift->updated_at,
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function checkInShift(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'shift_id' => 'required|exists:shifts,id',
                'location' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->all()
                ]);
            }

            $shift = Shift::where('status', 3)->findOrFail($request->shift_id);

            $claimedShift = ClaimShift::where('user_id', auth()->user()->id)
                ->where('shift_id', $request->shift_id)
                ->first();

            if (!$claimedShift) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shift not found'
                ]);
            }

            if ($claimedShift->check_in != '00:00:00') {
                return response()->json([
                    'success' => false,
                    'message' => 'Shift already checked in'
                ]);
            }

            // // 🕒 Validate time window
            // $currentTime = Carbon::now();
            // $shiftStart = Carbon::createFromFormat('H:i:s', date('H:i:s', strtotime($shift->start_time)));
            // $shiftEnd = Carbon::createFromFormat('H:i:s', date('H:i:s', strtotime($shift->end_time)));

            // // Optional buffer (e.g. allow check-in 15 minutes before and after start)
            // // $bufferMinutes = 15;
            // // $earliestAllowed = $shiftStart->copy()->subMinutes($bufferMinutes);
            // // $latestAllowed = $shiftEnd->copy()->addMinutes($bufferMinutes);

            // if ($currentTime->gt($shiftStart) || $currentTime->lt($shiftEnd)) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'You can only check in during your shift time.'
            //     ]);
            // }

            // 🕒 Validate time window (supports shifts crossing midnight)
            $currentTime = Carbon::now();
            $shiftStart = Carbon::createFromFormat('H:i:s', $shift->start_time);
            $shiftEnd = Carbon::createFromFormat('H:i:s', $shift->end_time);

            // Handle cross-midnight shift
            if ($shiftEnd->lt($shiftStart)) {
                // e.g. 9PM–3AM: shiftEnd is next day
                $shiftEnd->addDay();
            }

            // If current time is before start, but shift crosses midnight, adjust comparison base
            if ($currentTime->lt($shiftStart) && $shiftEnd->gt($shiftStart)) {
                $shiftStart->subDay();
                $shiftEnd->subDay();
            }

            // Optional: allow a small buffer around start time (e.g. ±15 min)
            $bufferMinutes = 15;
            $earliestAllowed = $shiftStart->copy()->subMinutes($bufferMinutes);
            $latestAllowed = $shiftEnd->copy()->addMinutes($bufferMinutes);

            // Check valid check-in time
            if (!$currentTime->between($earliestAllowed, $latestAllowed)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only check in during your shift time (between ' .
                        $shiftStart->format('g:i A') . ' and ' . $shiftEnd->format('g:i A') . ').',
                ]);
            }

            // ✅ Proceed to check in
            $claimedShift->update([
                'check_in' => now()->format('H:i:s'),
                'location' => $request->location,
            ]);

            $shift->update(['status' => 4]);

            return response()->json([
                'success' => true,
                'message' => 'Shift checked in successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function checkOutShift(ClaimShiftRequest $request)
    {
        try {
            $validated = $request->validated();
            $shift = Shift::where('status', 4)->findOrFail($validated['shift_id']);
            $user = auth()->user();
            if (!$shift->claimShift()->where('user_id', $user->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shift not found'
                ]);
            }
            if ($shift->claimShift()->where('user_id', $user->id)->first()->check_out != '00:00:00') {
                return response()->json([
                    'success' => false,
                    'message' => 'Shift already checked out'
                ]);
            }
            $shift->claimShift()->where('user_id', $user->id)->update([
                'check_out' => date('H:i:s'),
            ]);
            $shift->update([
                'status' => 5
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Shift checked out successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function confirmVerification(ClaimShiftRequest $request)
    {
        try {
            $validated = $request->validated();
            $shift = Shift::findOrFail($validated['shift_id']);
            $user = auth()->user();
            if ($shift->confirmVerification()->where('user_id', $user->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shift already verification'
                ]);
            }
            $shift->confirmVerification()->create([
                'user_id' => $user->id,
                'signature' => $request->signature->store('signature', 'public'),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Shift Verification successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function cancelledShift(ClaimShiftRequest $request)
    {
        try {
            $validated = $request->validated();
            $shift = Shift::where('status', 2)->findOrFail($validated['shift_id']);
            $user = auth()->user();
            // ✅ Otherwise, allow claiming
            $shift->claimShift()->delete();

            $shift->update([
                'status' => 1, // Opened
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Shift Cancelled successfully!',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function locationServices()
    {
        try {
            $user = auth()->user();
            $locationServices = ClaimShift::where('user_id', $user->id)->latest()->get();
            return response()->json([
                'success' => true,
                'location_services' => $locationServices
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
