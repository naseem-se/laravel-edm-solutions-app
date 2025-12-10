<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Http\Resources\FilledShiftResource;
use App\Models\ClaimShift;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Payment;

class HomeController extends Controller
{
    public function getShifts()
    {
        try {
            $user = auth()->user();

            // Only get shifts created by the logged-in facility
            $shifts = Shift::where('user_id', $user->id)
                ->whereIn('status', [1, 2, -1])
                ->orderBy('date', 'desc')
                ->get()
                ->groupBy('status');

            // Map the groups with readable names
            $data = [
                'open' => $shifts->get(1, collect())->values(),
                'pending' => $shifts->get(2, collect())->values(),
                'cancelled' => $shifts->get(-1, collect())->values(),
            ];

            return response()->json([
                'success' => true,
                'shifts' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get shifts: ' . $th->getMessage()
            ], 500);
        }
    }

    public function acceptPendingShift($id)
    {
        try {
            $shift = Shift::where('user_id', auth()->id())->where('status', 2)->findOrFail($id);
            // $claimedShift = ClaimShift::where('shift_id', $shift->id)->firstOrFail();
            $shift->update([
                'status' => 3
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Shift Confirmed Successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get shifts: ' . $th->getMessage()
            ], 500);
        }
    }

    public function filledShiftDetails()
    {
        try {
            $user = auth()->user();

            $shifts = Shift::with(['claimShift' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
                ->whereHas('claimShift', function ($query) use ($user) {})
                ->whereIn('status', [4, 5, 6])
                ->orderBy('date', 'desc')
                ->get()
                ->groupBy('status');


            $data = [
                'Awaiting' => FilledShiftResource::collection($shifts->get(4, collect())),
                'Completed' => FilledShiftResource::collection($shifts->get(5, collect())),
                'Paid' => FilledShiftResource::collection($shifts->get(6, collect())),
            ];

            return response()->json([
                'success' => true,
                'shifts' => $data,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get shifts: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function getCompleteShiftSummary($id)
    {
        try {
            $shift = Shift::with('claimShift')
                ->where('status', 5)
                ->where('id', $id)
                ->firstOrFail();

            $checkIn = $shift->claimShift?->check_in ? Carbon::parse($shift->claimShift->check_in) : null;
            $checkOut = $shift->claimShift?->check_out ? Carbon::parse($shift->claimShift->check_out) : null;

            $workedMinutes = 0;
            if ($checkIn && $checkOut) {
                // handle overnight shifts (e.g. 9 PM → 3 AM)
                if ($checkOut->lessThan($checkIn)) {
                    $checkOut->addDay();
                }
                $workedMinutes = $checkIn->diffInMinutes($checkOut);
            }

            // Convert minutes to hours
            $workedHours = floor($workedMinutes / 60);
            $remainingMinutes = $workedMinutes % 60;

            // Calculate total pay based on hourly rate
            $rate = $shift->pay_per_hour ?? 0;
            $totalAmount = round(($workedMinutes / 60) * $rate, 2);

            // Format display
            return response()->json([
                'success' => true,
                'data' => [
                    'total_shifts' => 1,
                    'worked_time' => sprintf("%d hr %d min", $workedHours, $remainingMinutes),
                    'total_amount' => '$' . number_format($totalAmount, 2),
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function StaffAttendanceDetails()
    {
        try {
            $userId = auth()->user()->id;
            $logs = ClaimShift::with(['user', 'shift'])
                ->whereHas('shift', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $data = $logs->map(function ($log) {
                return [
                    "name" => $log->user->full_name,

                    "shift_time" =>
                    Carbon::parse($log->shift->start_time)->format('g:i A') .
                        " To " .
                        Carbon::parse($log->shift->end_time)->format('g:i A'),

                    "date" => Carbon::parse($log->created_at)->format('M d, Y'),

                    "clocked_in" => Carbon::parse($log->check_in)->format('g:i A'),
                    "clocked_out" => Carbon::parse($log->check_out)->format('g:i A'),
                ];
            });

            return response()->json([
                'success' => true,
                'shift_attendance_details' => $data,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get shifts: ' . $th->getMessage()
            ], 500);
        }
    }

    public function getPaymentHistory()
    {
        try {
            $user = auth()->user();

            $payments = Payment::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'payments' => $payments,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get payments: ' . $th->getMessage()
            ], 500);
        }
    }   
}
