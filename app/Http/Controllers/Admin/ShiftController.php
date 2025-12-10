<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClaimShift;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $shifts = Shift::with('claimShift', 'claimShift.user')
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('license_type', 'like', '%' . $request->search . '%')
                        ->orWhere('special_instruction', 'like', '%' . $request->search . '%')
                        ->orWhere('location', 'like', '%' . $request->search . '%')
                        ->orWhere('title', 'like', '%' . $request->search . '%');
                });
            })->latest()->paginate(10);

        $claimedShifts = ClaimShift::with('shift', 'user')
            ->where('check_out', '00:00:00')
            ->where('check_in', '!=', '00:00:00')
            ->latest()
            ->get()
            ->map(function ($item) {
                $shiftStart = Carbon::parse($item->shift->start_time);
                $shiftEnd = Carbon::parse($item->shift->end_time);
                $checkIn = Carbon::parse($item->check_in);
                $now = Carbon::now();

                // 🕐 Fix: Handle overnight shift (e.g., 9 PM → 3 AM)
                if ($shiftEnd->lessThan($shiftStart)) {
                    $shiftEnd->addDay();
                }

                // 🧮 Calculate shift duration status
                if ($now->lessThan($shiftEnd)) {
                    // Shift still in progress
                    $item->status = 'In Progress';
                    $item->duration = $checkIn->diffForHumans($now, true); // e.g. "9 minutes"
                    $item->overtime = null;
                } else {
                    // Shift ended but no checkout
                    $item->status = 'Awaiting Checkout';
                    $item->duration = $checkIn->diffForHumans($shiftEnd, true);
                    $item->overtime = $shiftEnd->diffForHumans($now, true);
                }

                return $item;
            });

        return view('pages.admin.shifts', compact('shifts', 'claimedShifts'));
    }

    public function approvedShifts(Request $request)
    {
        $shifts = Shift::with('claimShift', 'claimShift.user')
            ->where('status', 1)
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('license_type', 'like', '%' . $request->search . '%')
                        ->orWhere('special_instruction', 'like', '%' . $request->search . '%')
                        ->orWhere('location', 'like', '%' . $request->search . '%')
                        ->orWhere('title', 'like', '%' . $request->search . '%');
                });
            })->latest()->paginate(10);

        $claimedShifts = ClaimShift::with('shift', 'user')
            ->where('check_out', '00:00:00')
            ->where('check_in', '!=', '00:00:00')
            ->latest()
            ->get()
            ->map(function ($item) {
                $shiftStart = Carbon::parse($item->shift->start_time);
                $shiftEnd = Carbon::parse($item->shift->end_time);
                $checkIn = Carbon::parse($item->check_in);
                $now = Carbon::now();

                // 🕐 Fix: Handle overnight shift (e.g., 9 PM → 3 AM)
                if ($shiftEnd->lessThan($shiftStart)) {
                    $shiftEnd->addDay();
                }

                // 🧮 Calculate shift duration status
                if ($now->lessThan($shiftEnd)) {
                    // Shift still in progress
                    $item->status = 'In Progress';
                    $item->duration = $checkIn->diffForHumans($now, true); // e.g. "9 minutes"
                    $item->overtime = null;
                } else {
                    // Shift ended but no checkout
                    $item->status = 'Awaiting Checkout';
                    $item->duration = $checkIn->diffForHumans($shiftEnd, true);
                    $item->overtime = $shiftEnd->diffForHumans($now, true);
                }

                return $item;
            });

        return view('pages.admin.shifts', compact('shifts', 'claimedShifts'));
    }

    public function calenderView(Request $request)
    {
        $view = $request->view ?? 'week'; // week or month

        if ($view == 'month') {
            // Month View
            $monthStart = $request->month
                ? Carbon::parse($request->month)->startOfMonth()
                : Carbon::now()->startOfMonth();

            $monthEnd = $monthStart->copy()->endOfMonth();

            // Get previous and next month dates
            $prevMonth = $monthStart->copy()->subMonth()->format('Y-m-d');
            $nextMonth = $monthStart->copy()->addMonth()->format('Y-m-d');
            $currentMonth = Carbon::now()->startOfMonth()->format('Y-m-d');

            // Get shifts for the month
            $shifts = Shift::whereBetween('date', [$monthStart, $monthEnd])
                ->with(['user'])
                ->get()
                ->groupBy('date');

            // Create days array for the month (including padding)
            $firstDayOfWeek = $monthStart->copy()->startOfWeek();
            $lastDayOfWeek = $monthEnd->copy()->endOfWeek();
            $totalDays = $firstDayOfWeek->diffInDays($lastDayOfWeek) + 1;

            $days = [];
            for ($i = 0; $i < $totalDays; $i++) {
                $date = $firstDayOfWeek->copy()->addDays($i);
                $days[] = [
                    'date' => $date,
                    'isCurrentMonth' => $date->month == $monthStart->month,
                    'shifts' => $shifts->get($date->format('Y-m-d'), collect())
                ];
            }

            // Get stats for the month
            $totalShifts = Shift::whereBetween('date', [$monthStart, $monthEnd])->count();
            $openShifts = Shift::whereBetween('date', [$monthStart, $monthEnd])->where('status', 1)->count();
            $filledShifts = Shift::whereBetween('date', [$monthStart, $monthEnd])->where('status', -1)->count();

            return view('pages.admin.calender-view', compact(
                'days',
                'monthStart',
                'prevMonth',
                'nextMonth',
                'currentMonth',
                'view',
                'totalShifts',
                'openShifts',
                'filledShifts'
            ));
        } else {
            // Week View
            $weekStart = $request->week
                ? Carbon::parse($request->week)->startOfWeek()
                : Carbon::now()->startOfWeek();

            $weekEnd = $weekStart->copy()->endOfWeek();

            $prevWeek = $weekStart->copy()->subWeek()->format('Y-m-d');
            $nextWeek = $weekStart->copy()->addWeek()->format('Y-m-d');
            $currentWeek = Carbon::now()->startOfWeek()->format('Y-m-d');

            $shifts = Shift::whereBetween('date', [$weekStart, $weekEnd])
                ->with(['user'])
                ->get()
                ->groupBy('date');

            $days = [];
            for ($i = 0; $i < 7; $i++) {
                $date = $weekStart->copy()->addDays($i);
                $days[] = [
                    'date' => $date,
                    'shifts' => $shifts->get($date->format('Y-m-d'), collect())
                ];
            }

            // Get stats for the week
            $totalShifts = Shift::whereBetween('date', [$weekStart, $weekEnd])->count();
            $openShifts = Shift::whereBetween('date', [$weekStart, $weekEnd])->where('status', 1)->count();
            $filledShifts = Shift::whereBetween('date', [$weekStart, $weekEnd])->where('status', -1)->count();

            return view('pages.admin.calender-view', compact(
                'days',
                'weekStart',
                'prevWeek',
                'nextWeek',
                'currentWeek',
                'view',
                'totalShifts',
                'openShifts',
                'filledShifts'
            ));
        }
    }

    public function approved($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            $shift->status = 1;
            $shift->save();
            return redirect()->back()->with('success', 'Shift Approved Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('success', $th->getMessage());
        }
    }

    public function cancelled($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            $shift->status = -1;
            $shift->save();
            return redirect()->back()->with('success', 'Shift Cancelled Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('success', $th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            $shift->delete();
            return redirect()->back()->with('success', 'Shift Deleted Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('success', $th->getMessage());
        }
    }

    public function show($id)
    {
        $shift = Shift::with('user')->findOrFail($id);
        return response()->json($shift);
    }

    public function openShifts(Request $request){
        $shifts = Shift::when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('license_type', 'like', '%' . $request->search . '%')
                        ->orWhere('special_instruction', 'like', '%' . $request->search . '%')
                        ->orWhere('location', 'like', '%' . $request->search . '%')
                        ->orWhere('title', 'like', '%' . $request->search . '%');
                });
            })
            ->where('status','1')
            ->latest()->paginate(10);
        
        $availableStaff = User::where('role', 'worker_mode')
                        ->where('is_verified', 1)
                        ->whereDoesntHave('claimShifts', function ($q) {
                            $q->whereDate('created_at', Carbon::today());
                        })
                        ->count();
        $avgRate = number_format(Shift::avg('pay_per_hour'),2);

        return view('pages.admin.open-shifts',compact('shifts','availableStaff','avgRate'));
    }
}
