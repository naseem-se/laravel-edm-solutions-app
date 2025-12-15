<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\User;
use App\Models\Document;
use App\Models\Payment;
use App\Models\ClaimShift;
use Carbon\Carbon;
use Spatie\Activitylog\Facades\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        // ==================== SHIFTS TODAY ====================
        $shiftsToday = Shift::whereDate('date', today())->count();
        $shiftsLastWeekToday = Shift::whereDate('date', today()->subWeek())->count();
        $shiftsTodayChange = $shiftsLastWeekToday > 0
            ? round((($shiftsToday - $shiftsLastWeekToday) / $shiftsLastWeekToday) * 100, 0)
            : 0;

        // ==================== ACTIVE STAFF ====================
        // Active staff = users who have claimed shifts this week
        $activeStaff = ClaimShift::whereDate('created_at', '>=', today()->subWeek())
            ->select('user_id')
            ->distinct()
            ->count();

        $activeStaffLastWeek = ClaimShift::whereDate('created_at', '>=', today()->subWeeks(2))
            ->whereDate('created_at', '<', today()->subWeek())
            ->select('user_id')
            ->distinct()
            ->count();

        $activeStaffChange = $activeStaffLastWeek > 0
            ? round((($activeStaff - $activeStaffLastWeek) / $activeStaffLastWeek) * 100, 0)
            : 0;

        // ==================== EXPIRING CREDENTIALS ====================
        // Documents expiring within 30 days (created_at + 365 days)
        $validityDays = 365;

        $expiringCredentials = Document::whereDate('created_at', '<=', today()->subDays($validityDays - 30))
            ->whereDate('created_at', '>', today()->subDays($validityDays))
            ->count();

        $expiringCredentialsLastWeek = Document::whereDate('created_at', '<=', today()->subWeek()->subDays($validityDays - 30))
            ->whereDate('created_at', '>', today()->subWeek()->subDays($validityDays))
            ->count();

        $expiringCredentialsChange = $expiringCredentialsLastWeek > 0
            ? round((($expiringCredentials - $expiringCredentialsLastWeek) / $expiringCredentialsLastWeek) * 100, 0)
            : 0;

        // ==================== PENDING TIMESHEETS ====================
        // Shifts with status 3 or 4 (pending/incomplete)
        $pendingTimesheets = Shift::whereIn('status', [3, 4])->count();

        $pendingTimesheetsLastWeek = Shift::whereIn('status', [3, 4])
            ->whereDate('created_at', '>=', today()->subWeek())
            ->whereDate('created_at', '<', today())
            ->count();

        $pendingTimesheetsChange = $pendingTimesheetsLastWeek > 0
            ? round((($pendingTimesheets - $pendingTimesheetsLastWeek) / $pendingTimesheetsLastWeek) * 100, 0)
            : 0;

        // ==================== REVENUE MTD (Month To Date) ====================
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        $revenueMTD = Payment::where('status', 'completed')
            ->whereDate('created_at', '>=', $monthStart)
            ->whereDate('created_at', '<=', $monthEnd)
            ->sum('amount');

        $revenueLastMonth = Payment::where('status', 'completed')
            ->whereDate('created_at', '>=', $lastMonthStart)
            ->whereDate('created_at', '<=', $lastMonthEnd)
            ->sum('amount');

        $revenueMTDChange = $revenueLastMonth > 0
            ? round((($revenueMTD - $revenueLastMonth) / $revenueLastMonth) * 100, 0)
            : 0;

        $formattedRevenueMTD = $this->formatRevenue($revenueMTD);

        // ==================== COMPILE ALL STATS ====================
        $stats = [
            'shiftsToday' => [
                'value' => $shiftsToday,
                'label' => 'Shifts Today',
                'icon' => 'clock',
                'color' => 'blue',
                'change' => $shiftsTodayChange,
                'changeType' => $shiftsTodayChange >= 0 ? 'increase' : 'decrease'
            ],
            'activeStaff' => [
                'value' => $activeStaff,
                'label' => 'Active Staff',
                'icon' => 'users',
                'color' => 'purple',
                'change' => $activeStaffChange,
                'changeType' => $activeStaffChange >= 0 ? 'increase' : 'decrease'
            ],
            'expiringCredentials' => [
                'value' => $expiringCredentials,
                'label' => 'Expiring Credentials',
                'icon' => 'alert',
                'color' => 'orange',
                'change' => abs($expiringCredentialsChange),
                'changeType' => $expiringCredentialsChange <= 0 ? 'decrease' : 'increase'
            ],
            'pendingTimesheets' => [
                'value' => $pendingTimesheets,
                'label' => 'Pending Timesheets',
                'icon' => 'document',
                'color' => 'yellow',
                'change' => abs($pendingTimesheetsChange),
                'changeType' => $pendingTimesheetsChange <= 0 ? 'decrease' : 'increase'
            ],
            'revenueMTD' => [
                'value' => $formattedRevenueMTD,
                'label' => 'Rev MTD',
                'icon' => 'currency',
                'color' => 'green',
                'change' => $revenueMTDChange,
                'changeType' => $revenueMTDChange >= 0 ? 'increase' : 'decrease'
            ]
        ];

        $demandSupplyData = $this->getDemandSupplyData();
        $fillRiskAlerts = $this->getFillRiskAlerts();
        $userGrowthTrendData = $this->getUserGrowthTrendData();
        $shiftDistributionData = $this->getShiftDistributionData();
        $todaysSchedule = $this->getTodaysSchedule();
        $recentActivity = $this->getRecentActivity();

        return view('pages.admin.dashboard4', compact('stats', 'demandSupplyData', 'fillRiskAlerts', 'userGrowthTrendData', 'shiftDistributionData', 'todaysSchedule', 'recentActivity'));
    }

    // Helper method to format revenue
    private function formatRevenue($amount)
    {
        if ($amount >= 1000000) {
            return '$' . round($amount / 1000000, 1) . 'M';
        } elseif ($amount >= 1000) {
            return '$' . round($amount / 1000, 1) . 'K';
        }
        return '$' . $amount;
    }

    private function getDemandSupplyData()
    {
        $days = [];
        $demandData = [];      // Total shifts needed (all shifts)
        $filledData = [];      // Shifts that are filled (have claim shifts with status 5 or 6)
        $supplyData = [];      // Available staff (unique users who claimed shifts this week)

        // Get data for last 7 days (Mon-Sun)
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayLabel = $date->format('D');
            $dayDate = $date->format('Y-m-d');

            $days[] = $dayLabel;

            // ===== DEMAND: Total shifts needed on this day =====
            $totalDemand = Shift::whereDate('date', $dayDate)->count();
            $demandData[] = $totalDemand;

            // ===== FILLED: Shifts with completed claim shifts (status 5 or 6) =====
            $filledShifts = Shift::whereDate('date', $dayDate)
                ->whereHas('claimShift', function ($q) {
                    $q->whereIn('status', [5, 6]);
                })
                ->count();
            $filledData[] = $filledShifts;

            // ===== SUPPLY: Unique staff who claimed shifts on this day =====
            $availableStaff = ClaimShift::whereDate('created_at', $dayDate)
                ->select('user_id')
                ->distinct()
                ->count();
            $supplyData[] = $availableStaff;
        }

        // Calculate totals
        $totalDemand = array_sum($demandData);
        $totalFilled = array_sum($filledData);
        $totalSupply = array_sum($supplyData);

        // Calculate fill rate
        $fillRate = $totalDemand > 0 ? round(($totalFilled / $totalDemand) * 100, 1) : 0;

        // Calculate max value for Y-axis scaling
        $allValues = array_merge($demandData, $filledData, $supplyData);
        $maxValue = count($allValues) > 0 ? ceil(max($allValues) / 10) * 10 + 10 : 60;

        return [
            'labels' => $days,
            'demand' => $demandData,
            'filled' => $filledData,
            'supply' => $supplyData,
            'totalDemand' => $totalDemand,
            'totalFilled' => $totalFilled,
            'totalSupply' => $totalSupply,
            'fillRate' => $fillRate,
            'maxValue' => $maxValue
        ];
    }

    private function getFillRiskAlerts()
    {
        // Get shifts from today onwards (next 14 days for risk assessment)
        $upcomingShifts = Shift::where('date', '>=', today())
            ->where('date', '<=', today()->addDays(14))
            ->with('user', 'claimShift')
            ->get();

        $facilityDayRisks = [];

        foreach ($upcomingShifts as $shift) {
            $facilityId = $shift->user_id;
            $facilityName = $shift->user->full_name ?? 'Unknown Facility';
            $shiftDate = $shift->date;
            $dateKey = $shiftDate;
            $riskKey = "{$facilityId}_{$dateKey}"; // Unique key for facility + date combination

            // Check if shift is filled (has claim shift with status 5 or 6)
            $isFilled = $shift->claimShift && in_array($shift->claimShift->status, [5, 6]);

            // Initialize facility-day risk data if not exists
            if (!isset($facilityDayRisks[$riskKey])) {
                $facilityDayRisks[$riskKey] = [
                    'facilityId' => $facilityId,
                    'facilityName' => $facilityName,
                    'shiftDate' => $shiftDate,
                    'dateKey' => $dateKey,
                    'totalShifts' => 0,
                    'unfilledShifts' => 0,
                ];
            }

            $facilityDayRisks[$riskKey]['totalShifts']++;

            if (!$isFilled) {
                $facilityDayRisks[$riskKey]['unfilledShifts']++;
            }
        }

        // Calculate risk levels and fill percentages
        $alerts = [];
        foreach ($facilityDayRisks as $risk) {
            $fillPercentage = $risk['totalShifts'] > 0
                ? round((($risk['totalShifts'] - $risk['unfilledShifts']) / $risk['totalShifts']) * 100, 0)
                : 100;

            // Only include if there are unfilled shifts
            if ($risk['unfilledShifts'] > 0) {
                // Calculate risk level based on date urgency AND fill percentage
                $riskData = $this->calculateRiskLevel(
                    $risk['shiftDate'],
                    $fillPercentage,
                    $risk['unfilledShifts'],
                    $risk['totalShifts']
                );

                $alerts[] = [
                    'facilityName' => $risk['facilityName'],
                    'riskLevel' => $riskData['riskLevel'],
                    'riskColor' => $riskData['riskColor'],
                    'priority' => $riskData['priority'],
                    'unfilledShifts' => $risk['unfilledShifts'],
                    'totalShifts' => $risk['totalShifts'],
                    'shiftDate' => $risk['shiftDate'],
                    'dateKey' => $risk['dateKey'],
                    'fillPercentage' => $fillPercentage,
                    'daysUntilShift' => today()->diffInDays($risk['shiftDate']),
                    'progressBarColor' => $riskData['progressBarColor'],
                    'badgeColor' => $riskData['badgeColor'],
                ];
            }
        }

        // Sort by priority (HIGH > MEDIUM > LOW), then by date (soonest first), then by unfilled shifts
        usort($alerts, function ($a, $b) {
            if ($a['priority'] !== $b['priority']) {
                return $a['priority'] <=> $b['priority'];
            }
            if ($a['shiftDate'] != $b['shiftDate']) {
                return $a['shiftDate'] <=> $b['shiftDate'];
            }
            return $b['unfilledShifts'] <=> $a['unfilledShifts'];
        });

        // Return top 5 alerts
        return array_slice($alerts, 0, 5);
    }

    // Calculate risk level based solely on date proximity
    private function calculateRiskLevel($shiftDate, $fillPercentage, $unfilledShifts, $totalShifts)
    {
        $daysUntilShift = today()->diffInDays($shiftDate);

        // Risk calculation based ONLY on date proximity:
        // - Today (0 days): HIGH RISK - Red
        // - Tomorrow (1 day): MEDIUM RISK - Yellow
        // - Day after tomorrow (2 days) and beyond: LOW RISK - Blue

        if ($daysUntilShift == 0) {
            // TODAY
            $riskLevel = 'HIGH RISK';
            $riskColor = 'red';
            $priority = 1;
        } elseif ($daysUntilShift == 1) {
            // TOMORROW
            $riskLevel = 'MEDIUM RISK';
            $riskColor = 'yellow';
            $priority = 2;
        } else {
            // DAY AFTER TOMORROW and beyond
            $riskLevel = 'LOW RISK';
            $riskColor = 'blue';
            $priority = 3;
        }

        // Set progress bar and badge colors
        $progressBarColor = match ($riskColor) {
            'red' => 'bg-red-600',
            'yellow' => 'bg-yellow-500',
            'blue' => 'bg-blue-500',
            default => 'bg-gray-400'
        };

        $badgeColor = match ($riskColor) {
            'red' => 'bg-red-600',
            'yellow' => 'bg-yellow-600',
            'blue' => 'bg-blue-600',
            default => 'bg-gray-600'
        };

        return [
            'riskLevel' => $riskLevel,
            'riskColor' => $riskColor,
            'priority' => $priority,
            'progressBarColor' => $progressBarColor,
            'badgeColor' => $badgeColor,
        ];
    }

    private function getUserGrowthTrendData()
    {
        $months = [];
        $userCounts = [];

        // Get data for last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->startOfMonth();
            $monthEnd = $date->endOfMonth();
            $monthLabel = $date->format('M');

            // Total cumulative users up to end of this month
            $totalUsers = User::whereDate('created_at', '<=', $monthEnd)->count();

            $months[] = $monthLabel;
            $userCounts[] = $totalUsers;
        }

        // Calculate max value for Y-axis scaling
        $maxValue = count($userCounts) > 0 ? ceil(max($userCounts) / 100) * 100 + 100 : 600;

        return [
            'labels' => $months,
            'data' => $userCounts,
            'maxValue' => $maxValue
        ];
    }

    // Generate shift distribution data (Completed, Canceled, Pending)
    private function getShiftDistributionData()
    {
        // Get all shifts
        $allShifts = Shift::count();

        // Calculate shift statuses
        // Status 5 & 6 = Completed
        $completedShifts = Shift::whereIn('status', [5, 6])->count();

        // Status -1 = Canceled
        $canceledShifts = Shift::where('status', -1)->count();

        // Status 3 & 4 = Pending
        $pendingShifts = Shift::whereIn('status', [0, 1, 2, 3, 4])->count();

        // Calculate percentages
        $completedPercentage = $allShifts > 0 ? round(($completedShifts / $allShifts) * 100, 0) : 0;
        $canceledPercentage = $allShifts > 0 ? round(($canceledShifts / $allShifts) * 100, 0) : 0;
        $pendingPercentage = $allShifts > 0 ? round(($pendingShifts / $allShifts) * 100, 0) : 0;

        // Build labels with percentages
        $labels = [
            "Completed {$completedPercentage}%",
            "Canceled {$canceledPercentage}%",
            "Pending {$pendingPercentage}%"
        ];

        $data = [
            $completedPercentage,
            $canceledPercentage,
            $pendingPercentage
        ];

        return [
            'labels' => $labels,
            'data' => $data,
            'completedShifts' => $completedShifts,
            'canceledShifts' => $canceledShifts,
            'pendingShifts' => $pendingShifts,
            'totalShifts' => $allShifts
        ];
    }

    // Get today's schedule with shift details
    private function getTodaysSchedule()
    {
        // Get all shifts for today, ordered by time
        $shifts = Shift::where('date', today())
            ->with('user', 'claimShift')
            ->orderBy('start_time') // Assuming you have a start_time column
            ->get();

        $scheduleItems = [];

        foreach ($shifts as $shift) {
            // Determine shift status based on claim shift
            $isFilled = $shift->claimShift && in_array($shift->claimShift->status, [5, 6]);
            $isPending = $shift->claimShift && in_array($shift->claimShift->status, [3, 4]);

            if ($isFilled) {
                $statusLabel = 'filled';
                $statusColor = 'bg-green-100 text-green-800';
            } elseif ($isPending) {
                $statusLabel = 'pending';
                $statusColor = 'bg-yellow-100 text-yellow-800';
            } else {
                // Check if shift needs urgent attention (within 4 hours)
                $shiftStartTime = Carbon::parse($shift->start_time);
                $hoursUntilShift = now()->diffInHours($shiftStartTime, false);

                if ($hoursUntilShift < 4 && $hoursUntilShift > 0) {
                    $statusLabel = 'urgent';
                    $statusColor = 'bg-red-100 text-red-800';
                } else {
                    $statusLabel = 'open';
                    $statusColor = 'bg-gray-900 text-white';
                }
            }

            $scheduleItems[] = [
                'facilityName' => $shift->user->full_name ?? 'Unknown Facility',
                'shiftTime' => $shift->start_time ? \Carbon\Carbon::parse($shift->start_time)->format('g:i A') : 'N/A',
                'statusLabel' => $statusLabel,
                'statusColor' => $statusColor,
                'shiftId' => $shift->id,
            ];
        }

        return $scheduleItems;
    }

    // Get weekly schedule (Monday to Sunday of current week)
    public function getWeeklySchedule()
    {
        $mondayOfCurrentWeek = today()->startOfWeek(); // Monday
        $sundayOfCurrentWeek = today()->endOfWeek();   // Sunday

        $shifts = Shift::whereDate('date', '>=', $mondayOfCurrentWeek)
            ->whereDate('date', '<=', $sundayOfCurrentWeek)
            ->with('user', 'claimShift')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $scheduleItems = $this->formatScheduleItems($shifts);

        return response()->json([
            'success' => true,
            'scheduleItems' => $scheduleItems,
            'period' => 'Weekly'
        ]);
    }

    // Get monthly schedule
    public function getMonthlySchedule()
    {
        $shifts = Shift::whereDate('date', '>=', today()->startOfMonth())
            ->whereDate('date', '<=', today()->endOfMonth())
            ->with('user', 'claimShift')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $scheduleItems = $this->formatScheduleItems($shifts);

        return response()->json([
            'success' => true,
            'scheduleItems' => $scheduleItems,
            'period' => 'Monthly'
        ]);
    }

    // Format schedule items helper
    private function formatScheduleItems($shifts)
    {
        $scheduleItems = [];

        foreach ($shifts as $shift) {
            // Determine shift status
            $isFilled = $shift->claimShift && in_array($shift->claimShift->status, [5, 6]);
            $isPending = $shift->claimShift && in_array($shift->claimShift->status, [3, 4]);

            if ($isFilled) {
                $statusLabel = 'filled';
                $statusColor = 'bg-green-100 text-green-800';
            } elseif ($isPending) {
                $statusLabel = 'pending';
                $statusColor = 'bg-yellow-100 text-yellow-800';
            } else {
                $statusLabel = 'open';
                $statusColor = 'bg-gray-900 text-white';
            }

            $scheduleItems[] = [
                'facilityName' => $shift->user->full_name ?? 'Unknown Facility',
                'shiftDate' => $shift->date,
                'shiftTime' => $shift->start_time ? Carbon::parse($shift->start_time)->format('g:i A') : 'N/A',
                'statusLabel' => $statusLabel,
                'statusColor' => $statusColor,
                'shiftId' => $shift->id,
            ];
        }

        return $scheduleItems;
    }

    // Get recent activity from activity log
private function getRecentActivity()
{
    // Get last 10 activities from the activity log
    $activities = \Spatie\Activitylog\Models\Activity::latest('created_at')
        ->limit(10)
        ->get();

    $activityFeed = [];

    foreach ($activities as $activity) {
        $activityItem = $this->formatActivityItem($activity);
        if ($activityItem) {
            $activityFeed[] = $activityItem;
        }
    }

    return $activityFeed;
}

// Format activity item based on type and event
private function formatActivityItem($activity)
{
    $logName = $activity->log_name;
    $description = $activity->description;
    $event = $activity->event;
    $subjectType = $activity->subject_type;
    $subjectId = $activity->subject_id;
    $properties = $activity->properties ?? [];
    $timeAgo = $activity->created_at->diffForHumans();

    // Get causer (who performed the action)
    $causerName = 'System';
    if ($activity->causer) {
        $causerName = $activity->causer->full_name ?? $activity->causer->name ?? 'Unknown';
    }

    // Parse subject type
    $modelName = class_basename($subjectType);

    // User-related activities
    if (str_contains($subjectType, 'User')) {
        if ($event === 'created') {
            return [
                'type' => 'user_registered',
                'icon' => 'user',
                'iconBgColor' => 'bg-blue-100',
                'iconColor' => 'text-blue-600',
                'title' => 'New staff registered',
                'description' => $description . ' by ' . $causerName,
                'timeAgo' => $timeAgo,
            ];
        } elseif ($event === 'updated') {
            return [
                'type' => 'user_updated',
                'icon' => 'user',
                'iconBgColor' => 'bg-blue-100',
                'iconColor' => 'text-blue-600',
                'title' => 'Staff information updated',
                'description' => 'User #' . $subjectId . ' was updated',
                'timeAgo' => $timeAgo,
            ];
        }
    }

    // Shift-related activities
    if (str_contains($subjectType, 'Shift')) {
        if ($event === 'created') {
            return [
                'type' => 'shift_created',
                'icon' => 'document',
                'iconBgColor' => 'bg-purple-100',
                'iconColor' => 'text-purple-600',
                'title' => 'New shift posted',
                'description' => 'Shift #' . $subjectId . ' was created',
                'timeAgo' => $timeAgo,
            ];
        } elseif ($event === 'updated') {
            return [
                'type' => 'shift_updated',
                'icon' => 'document',
                'iconBgColor' => 'bg-yellow-100',
                'iconColor' => 'text-yellow-600',
                'title' => 'Shift updated',
                'description' => 'Shift #' . $subjectId . ' was modified',
                'timeAgo' => $timeAgo,
            ];
        }
    }

    // ClaimShift activities (shift filled)
    if (str_contains($subjectType, 'ClaimShift')) {
        if ($event === 'created') {
            return [
                'type' => 'shift_filled',
                'icon' => 'check',
                'iconBgColor' => 'bg-green-100',
                'iconColor' => 'text-green-600',
                'title' => 'Shift claimed',
                'description' => $causerName . ' claimed a shift',
                'timeAgo' => $timeAgo,
            ];
        }
    }

    // Document activities
    if (str_contains($subjectType, 'Document')) {
        if ($event === 'created') {
            return [
                'type' => 'document_uploaded',
                'icon' => 'document',
                'iconBgColor' => 'bg-yellow-100',
                'iconColor' => 'text-yellow-600',
                'title' => 'Document uploaded',
                'description' => $causerName . ' uploaded a document',
                'timeAgo' => $timeAgo,
            ];
        } elseif ($event === 'updated') {
            return [
                'type' => 'document_updated',
                'icon' => 'document',
                'iconBgColor' => 'bg-yellow-100',
                'iconColor' => 'text-yellow-600',
                'title' => 'Document updated',
                'description' => 'Document #' . $subjectId . ' was updated',
                'timeAgo' => $timeAgo,
            ];
        }
    }

    // Payment activities
    if (str_contains($subjectType, 'Payment')) {
        if ($event === 'created') {
            $amount = $properties['attributes']['amount'] ?? $properties['amount'] ?? 0;
            return [
                'type' => 'payment_created',
                'icon' => 'currency',
                'iconBgColor' => 'bg-green-100',
                'iconColor' => 'text-green-600',
                'title' => 'Payment processed',
                'description' => 'Payment #' . $subjectId . ' of $' . number_format($amount, 2),
                'timeAgo' => $timeAgo,
            ];
        }
    }

    if($logName === 'Admin') {
        return [
            'type' => 'admin_action',
            'icon' => 'shield-check',
            'iconBgColor' => 'bg-gray-100',
            'iconColor' => 'text-gray-600',
            'title' => 'Admin Action - ' . ucfirst($event),
            'description' => $description . ' by ' . $causerName,
            'timeAgo' => $timeAgo,
        ];

    }

    if($logName === 'System') {
        return [
            'type' => 'system_action',
            'icon' => 'cog',
            'iconBgColor' => 'bg-gray-100',
            'iconColor' => 'text-gray-600',
            'title' => 'System Action - ' . ucfirst($event),
            'description' => $description,
            'timeAgo' => $timeAgo,
        ];
    }

    if($logName === 'login') {
        return [
            'type' => 'login_activity',
            'icon' => 'login',
            'iconBgColor' => 'bg-gray-100',
            'iconColor' => 'text-gray-600',
            'title' => 'Login Activity - ' . ucfirst($event),
            'description' => $description . ' by ' . $causerName,
            'timeAgo' => $timeAgo,
        ];
    }

    if($logName === 'platform_config') {
        return [
            'type' => 'platform_config',
            'icon' => 'cog',
            'iconBgColor' => 'bg-gray-100',
            'iconColor' => 'text-gray-600',
            'title' => 'Platform Config - ' . ucfirst($event),
            'description' => $description . ' by ' . $causerName,
            'timeAgo' => $timeAgo,
        ];
    }

    if($logName === 'register'){
        return [
            'type' => 'user_registration',
            'icon' => 'user-plus',
            'iconBgColor' => 'bg-gray-100',
            'iconColor' => 'text-gray-600',
            'title' => 'User Registration - ' . ucfirst($event),
            'description' => $description,
            'timeAgo' => $timeAgo,
        ];
    }

    if($logName === 'logout'){
        return [
            'type' => 'user_logout',
            'icon' => 'logout',
            'iconBgColor' => 'bg-gray-100',
            'iconColor' => 'text-gray-600',
            'title' => 'User Logout - ' . ucfirst($event),
            'description' => $description,
            'timeAgo' => $timeAgo,
        ];
    }

    if($logName === 'password_reset'){
        return [
            'type' => 'password_reset',
            'icon' => 'key',
            'iconBgColor' => 'bg-gray-100',
            'iconColor' => 'text-gray-600',
            'title' => 'Password Reset - ' . ucfirst($event),
            'description' => $description,
            'timeAgo' => $timeAgo,
        ];
    }

    if($logName === 'verfification'){
        return [
            'type' => 'verfification_activity',
            'icon' => 'mail',
            'iconBgColor' => 'bg-gray-100',
            'iconColor' => 'text-gray-600',
            'title' => 'Email Verification - ' . ucfirst($event),
            'description' => $description,
            'timeAgo' => $timeAgo,
        ];
    }

    if($logName === 'resend_otp'){
        return [
            'type' => 'resend_otp',
            'icon' => 'shield-check',
            'iconBgColor' => 'bg-gray-100',
            'iconColor' => 'text-gray-600',
            'title' => 'Resend OTP - ' . ucfirst($event),
            'description' => $description,
            'timeAgo' => $timeAgo,
        ];
    }

    if($logName === 'forgot_passord'){
        return [
            'type' => 'forgot_password',
            'icon' => 'key',
            'iconBgColor' => 'bg-gray-100',
            'iconColor' => 'text-gray-600',
            'title' => 'Forgot Password - ' . ucfirst($event),
            'description' => $description,
            'timeAgo' => $timeAgo,
        ];
    }

    return [
        'type' => 'generic',
        'icon' => 'document',
        'iconBgColor' => 'bg-gray-100',
        'iconColor' => 'text-gray-600',
        'title' => ucfirst($event) . ' - ' . $modelName,
        'description' => $description,
        'timeAgo' => $timeAgo,
    ];
}



}
