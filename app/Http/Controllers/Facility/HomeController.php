<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Http\Resources\FilledShiftResource;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\UserResource;
use App\Models\ClaimShift;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function getShifts()
    {
        try {
            $user = auth()->user();

            // Only get shifts created by the logged-in facility
            $shifts = Shift::where('user_id', $user->id)
                ->whereIn('status', [1, 2, 3, -1])
                ->orderBy('date', 'desc')
                ->get()
                ->groupBy('status');

            // Map the groups with readable names
            $data = [
                'open' => $shifts->get(1, collect())->values(),
                'pending' => $shifts->get(2, collect())->values(),
                'filled' => $shifts->get(3, collect())->values(),
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

            $shifts = Shift::with([
                'claimShift' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }
            ])
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

            $payments = Payment::with('payer', 'shift')->where('user_id', $user->id)
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

    public function getWorkersList(Request $request)
    {
        try {
            $workers = User::where('role', 'worker_mode')
                ->when($request->has('search') && !empty($request->search), function ($query) use ($request) {
                    $searchTerm = $request->search;
                    $query->where(function ($q) use ($searchTerm) {
                        $q->where('first_name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('email', 'like', '%' . $searchTerm . '%');
                    });
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $workers = UserResource::collection($workers);

            return response()->json([
                'success' => true,
                'workers' => $workers,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get workers: ' . $th->getMessage()
            ], 500);
        }
    }

    public function getWorkerDetails($id)
    {
        try {
            $worker = User::where('role', 'worker_mode')->findOrFail($id);

            return response()->json([
                'success' => true,
                'worker' => new UserResource($worker),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get worker details: ' . $th->getMessage()
            ], 500);
        }
    }

    public function getStatsReport()
    {
        try {
            $stats = $this->getStats();
            $monthlyShifts = $this->getMonthlyShiftsData();
            $costByDepartment = $this->getCostByDepartment();
            $shiftDistribution = $this->getShiftTitleDistribution();

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'monthly_filled_shifts' => $monthlyShifts,
                'cost_by_department' => $costByDepartment,
                'shift_title_distribution' => $shiftDistribution,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get stats: ' . $th->getMessage()
            ], 500);
        }
    }

    private function getStats()
    {
        $user = auth()->user();
        
        $thisMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();

        // Total Cost Paid to Workers
        $thisMonthCost = Payment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('created_at', '>=', $thisMonth)
            ->sum('amount');

        $lastMonthCost = Payment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('created_at', '>=', $lastMonth)
            ->where('created_at', '<', $thisMonth)
            ->sum('amount');

        $costChange = $lastMonthCost > 0 
            ? round((($thisMonthCost - $lastMonthCost) / $lastMonthCost) * 100, 0)
            : 0;

        // Total Shifts Completed
        $thisMonthShifts = Shift::where('user_id', $user->id)
            ->whereIn('status', [5,6])
            ->where('date', '>=', $thisMonth)
            ->count();

        $lastMonthShifts = Shift::where('user_id', $user->id)
            ->whereIn('status', [5,6])
            ->where('date', '>=', $lastMonth)
            ->where('date', '<', $thisMonth)
            ->count();

        $shiftsChange = $lastMonthShifts > 0
            ? round((($thisMonthShifts - $lastMonthShifts) / $lastMonthShifts) * 100, 0)
            : 0;

        // Average Cost Per Shift
        $avgCostPerShift = $thisMonthShifts > 0 
            ? round($thisMonthCost / $thisMonthShifts, 2)
            : 0;

        // Pending Payments to Workers
        $pendingPayments = Payment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');

        $nextPaymentDate = Payment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->min('created_at');
        
        $data = [
            'total_cost_paid' => [
                'amount' => $thisMonthCost,
                'change_percentage' => $costChange,
            ],
            'total_shifts_completed' => [
                'count' => $thisMonthShifts,
                'change_percentage' => $shiftsChange,
            ],
            'average_cost_per_shift' => $avgCostPerShift,
            'pending_payments' => [
                'amount' => $pendingPayments,
                'next_payment_date' => $nextPaymentDate ? Carbon::parse($nextPaymentDate)->toDateString() : null,
            ],
        ];

        return $data;
    }

    private function getMonthlyShiftsData()
    {
        $user = auth()->user();
        $months = 6; // Last 6 months
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M');
            
            $shiftCount = Shift::where('user_id', $user->id)
                ->whereIn('status', [3,4,5,6])
                ->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->count();

            $data[] = [
                'month' => $monthName,
                'shifts' => $shiftCount,
            ];
        }

        return $data;
    }

    private function getCostByDepartment()
    {
        $user = auth()->user();

        $departments = Payment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->join('users as workers', 'payments.recipient_id', '=', 'workers.id')
            ->select(
                'workers.department',
                DB::raw('SUM(payments.amount) as total_cost')
            )
            ->groupBy('workers.department')
            ->orderByDesc('total_cost')
            ->get();

        $data = $departments->map(fn($dept) => [
            'department' => $dept->department ?? 'Unassigned',
            'cost' => (float) $dept->total_cost,
            'cost_formatted' => number_format($dept->total_cost, 0),
        ])->values();

        return $data;
    }

    private function getShiftTitleDistribution()
    {
        $user = auth()->user();

        $shifts = Shift::where('user_id', $user->id)
            ->select(
                'title',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('title')
            ->orderByDesc('count')
            ->get();

        $total = $shifts->sum('count');

        $data = $shifts->map(function ($shift) use ($total) {
            $percentage = round(($shift->count / $total) * 100, 0);
            $initials = $this->getInitials($shift->title);

            return [
                'title' => $shift->title,
                'initials' => $initials,
                'count' => $shift->count,
                'percentage' => $percentage,
            ];
        })->values();

        return $data = [
            'total_shifts' => $total,
            'shifts_by_title' => $data,
        ];
    }

    private function getInitials($title)
    {
        $words = explode(' ', trim($title));
        $initials = '';

        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }

        return $initials;
    }

    public function getPaymentsReport()
    {
        try {
            $user = auth()->user();

            $payments = Payment::with('payer', 'shift')->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $stats = $this->getStats();
            return response()->json([
                'success' => true,
                'payments' => PaymentResource::collection($payments),
                'stats' => $stats,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get payments: ' . $th->getMessage()
            ], 500);
        }
    }

    
}
