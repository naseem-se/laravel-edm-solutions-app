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
            $shifts = Shift::
                // where('status', 1)
                // ->whereDate('date', $date) // assuming your shifts table has 'date' column
                with('claimShift')
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
        
            $claimed_shifts = $user->claimShifts()
                ->with('shift')
                ->latest()
                ->get();
        
            if ($claimed_shifts->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No claimed shifts found.',
                ]);
            }
        
            $now = Carbon::now();
        
            $data = $claimed_shifts->map(function ($claimed_shift) use ($now) {

                $shift = $claimed_shift->shift instanceof \Illuminate\Database\Eloquent\Collection
                    ? $claimed_shift->shift->first()
                    : $claimed_shift->shift;

                $shiftDate = $shift?->date ?? Carbon::today()->toDateString();

                $start = Carbon::parse($shiftDate . ' ' . $claimed_shift->start_time);
                $end = Carbon::parse($shiftDate . ' ' . $claimed_shift->end_time);

                $check_in_enabled = false;
                $check_out_enabled = false;

                if ($claimed_shift->check_in === '00:00:00' && $now->greaterThanOrEqualTo($start)) {
                    $check_in_enabled = true;
                }

                if (
                    $claimed_shift->check_in !== '00:00:00' &&
                    $claimed_shift->check_out === '00:00:00' &&
                    $now->greaterThanOrEqualTo($end)
                ) {
                    $check_out_enabled = true;
                }

                return [
                    'id' => $claimed_shift->id,
                    'shift_id' => $claimed_shift->shift_id,
                    'user_id' => $claimed_shift->user_id,
                    'start_time' => Carbon::parse($claimed_shift->start_time)->format('g:i A'),
                    'end_time' => Carbon::parse($claimed_shift->end_time)->format('g:i A'),
                    'check_in_enabled' => $check_in_enabled,
                    'check_out_enabled' => $check_out_enabled,
                    'location' => $claimed_shift->location,
                    'status'=> $this->getStatusText($claimed_shift->shift->status),
                    'created_at' => $claimed_shift->created_at->toDateTimeString(),
                ];
            });
        
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
    
    private function getStatusText($status)
    {
        return match ($status) {
            0 => 'Pending',
            1 => 'Opened',
            2 => 'Pending Approval',
            3 => 'Confirmed',
            4 => 'In Progress',
            5 => 'Completed',
            6 => 'Paid',
            -1 => 'Cancelled',
            default => 'Unknown',
        };
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

    public function filterShifts(Request $request)
    {
        try {
            $query = Shift::where('status', 1);

            if ($request->filled('start_date')) {
                $query->whereDate('date', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('date', '<=', $request->end_date);
            }

            if ($request->filled('location')) {
                $query->where('location', 'LIKE', "%{$request->location}%");
            }

            if ($request->filled('min_pay')) {
                $query->where('pay_per_hour', '>=', $request->min_pay);
            }

            if ($request->filled('max_pay')) {
                $query->where('pay_per_hour', '<=', $request->max_pay);
            }

            $shifts = $query->latest()->get();

            return response()->json([
                'success' => true,
                'data' => ShiftResource::collection($shifts),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    private const REGULAR_HOURS_LIMIT = 8;
    private const TIME_FORMATS = [
        'H:i:s',
        'H:i',
        'h:i A',
        'Y-m-d H:i:s',
        'Y-m-d H:i',
        'Y-m-d\TH:i:s',
    ];

    public function getWeeklyTimesheet()
    {
        $user = auth()->user();
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $shiftsThisWeek = ClaimShift::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->get();

        $weekData = $this->calculateWeekData($shiftsThisWeek, $startOfWeek);

        return response()->json([
            'success' => true,
            'data' => [
                'week_range' => $startOfWeek->format('M d') . '-' . $endOfWeek->format('M d'),
                'week_display' => 'Week of ' . $startOfWeek->format('M d') . '-' . $endOfWeek->format('M d'),
                'status' => $weekData['status'],
                'status_color' => $this->getStatusColor($weekData['status']),
                'summary' => $this->formatSummary($weekData['total'], $weekData['regular'], $weekData['overtime']),
                'daily_entries' => $weekData['daily'],
                'total_shifts' => $shiftsThisWeek->count(),
            ],
        ], 200);
    }

    public function getTimesheetByWeek($startDate)
    {
        $user = auth()->user();

        try {
            $weekStart = Carbon::createFromFormat('Y-m-d', $startDate)->startOfWeek();
            $weekEnd = $weekStart->clone()->endOfWeek();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid date format. Use Y-m-d',
            ], 400);
        }

        $shiftsThisWeek = ClaimShift::where('user_id', $user->id)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->get();

        $weekData = $this->calculateWeekData($shiftsThisWeek, $weekStart);

        return response()->json([
            'success' => true,
            'data' => [
                'week_range' => $weekStart->format('M d') . '-' . $weekEnd->format('M d'),
                'week_display' => 'Week of ' . $weekStart->format('M d') . '-' . $weekEnd->format('M d'),
                'status' => $weekData['status'],
                'status_color' => $this->getStatusColor($weekData['status']),
                'summary' => $this->formatSummary($weekData['total'], $weekData['regular'], $weekData['overtime']),
                'daily_entries' => $weekData['daily'],
                'total_shifts' => $shiftsThisWeek->count(),
            ],
        ], 200);
    }

    public function getMonthlyTimesheet($month = null, $year = null)
    {
        $user = auth()->user();
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->clone()->endOfMonth();

        $shiftsThisMonth = ClaimShift::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        $weeksData = [];
        $totalHours = 0;
        $regularHours = 0;
        $overtimeHours = 0;

        $weeks = $shiftsThisMonth->groupBy(fn($shift) => $shift->created_at->weekOfYear);

        foreach ($weeks as $week) {
            $weekStart = $week->first()->created_at->startOfWeek();
            $weekEnd = $weekStart->clone()->endOfWeek();

            $weekData = $this->calculateWeekHours($week);
            $totalHours += $weekData['total'];
            $regularHours += $weekData['regular'];
            $overtimeHours += $weekData['overtime'];

            $weeksData[] = [
                'week' => $weekStart->format('M d') . ' - ' . $weekEnd->format('M d'),
                'hours' => number_format($weekData['total'], 1),
                'regular' => number_format($weekData['regular'], 1),
                'overtime' => number_format($weekData['overtime'], 1),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'month' => $startOfMonth->format('F Y'),
                'summary' => $this->formatSummary($totalHours, $regularHours, $overtimeHours),
                'weeks' => $weeksData,
                'total_shifts' => $shiftsThisMonth->count(),
            ],
        ], 200);
    }

    private function calculateWeekData($shiftsThisWeek, $startOfWeek)
    {
        $totalHours = 0;
        $regularHours = 0;
        $overtimeHours = 0;
        $dailyEntries = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->clone()->addDays($i);

            $dayHours = $shiftsThisWeek
                ->filter(fn($shift) => $shift->created_at->isSameDay($date))
                ->sum(fn($shift) => $this->getShiftHours($shift));

            if ($dayHours > 0) {
                $regular = min($dayHours, self::REGULAR_HOURS_LIMIT);
                $overtime = max(0, $dayHours - self::REGULAR_HOURS_LIMIT);

                $regularHours += $regular;
                $overtimeHours += $overtime;
                $totalHours += $dayHours;

                $dailyEntries[] = [
                    'day' => $date->format('l'),
                    'date' => $date->format('M d'),
                    'hours' => number_format($dayHours, 1),
                    'hours_raw' => (float) $dayHours,
                    'is_overtime' => $dayHours > self::REGULAR_HOURS_LIMIT,
                ];
            }
        }

        return [
            'total' => $totalHours,
            'regular' => $regularHours,
            'overtime' => $overtimeHours,
            'daily' => $dailyEntries,
            'status' => $this->getWeekStatus($shiftsThisWeek),
        ];
    }

    private function calculateWeekHours($shifts)
    {
        $total = 0;
        $regular = 0;
        $overtime = 0;

        foreach ($shifts as $shift) {
            $hours = $this->getShiftHours($shift);
            $total += $hours;

            if ($hours > self::REGULAR_HOURS_LIMIT) {
                $regular += self::REGULAR_HOURS_LIMIT;
                $overtime += $hours - self::REGULAR_HOURS_LIMIT;
            } else {
                $regular += $hours;
            }
        }

        return [
            'total' => $total,
            'regular' => $regular,
            'overtime' => $overtime,
        ];
    }

    private function getShiftHours($shift)
    {
        try {
            if ($shift->check_in && $shift->check_out) {
                return $this->calculateHours($shift->check_in, $shift->check_out);
            }
            return $this->calculateHours($shift->start_time, $shift->end_time);
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function calculateHours($startTime, $endTime)
    {
        if ($startTime instanceof Carbon && $endTime instanceof Carbon) {
            $start = $startTime;
            $end = $endTime;
        } else {
            $start = $this->parseTime($startTime);
            $end = $this->parseTime($endTime);
        }

        if ($end->lessThan($start)) {
            $end->addDay();
        }

        return round($start->diffInMinutes($end) / 60, 1);
    }

    private function parseTime($time)
    {
        if ($time instanceof Carbon) {
            return $time;
        }

        if (is_null($time)) {
            return now();
        }

        $timeString = (string) $time;

        foreach (self::TIME_FORMATS as $format) {
            try {
                return Carbon::createFromFormat($format, $timeString);
            } catch (\Exception $e) {
                continue;
            }
        }

        try {
            return Carbon::parse($timeString);
        } catch (\Exception $e) {
            return now();
        }
    }

    private function getWeekStatus($shifts)
    {
        if ($shifts->isEmpty()) {
            return 'No Shifts';
        }

        $checkedOut = $shifts->whereNotNull('check_out')->count();
        $total = $shifts->count();

        return match (true) {
            $checkedOut === $total => 'Completed',
            $checkedOut > 0 => 'In Progress',
            default => 'Pending',
        };
    }

    private function getStatusColor($status)
    {
        return match ($status) {
            'Completed' => 'green',
            'In Progress' => 'blue',
            'Pending' => 'yellow',
            'No Shifts' => 'gray',
            default => 'gray',
        };
    }

    private function formatSummary($total, $regular, $overtime)
    {
        return [
            [
                'label' => 'Total Hours',
                'value' => number_format($total, 1) . ' hrs',
                'value_raw' => (float) $total,
            ],
            [
                'label' => 'Regular Hours',
                'value' => number_format($regular, 1) . ' hrs',
                'value_raw' => (float) $regular,
            ],
            [
                'label' => 'Overtime',
                'value' => number_format($overtime, 1) . ' hrs',
                'value_raw' => (float) $overtime,
            ],
        ];
    }

}
