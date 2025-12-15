<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\FilledShiftResource;
use App\Models\ClaimShift;
use App\Models\Shift;
use Illuminate\Support\Carbon;
use App\Models\Payment;
use App\Models\User;

class ReportController extends Controller
{

    public function index()
    {
        // Get current and previous month dates
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        // ==================== TOTAL USERS ====================
        $currentUsers = User::whereDate('created_at', '>=', $currentMonthStart)
            ->whereDate('created_at', '<=', $currentMonthEnd)
            ->count();

        $lastMonthUsers = User::whereDate('created_at', '>=', $lastMonthStart)
            ->whereDate('created_at', '<=', $lastMonthEnd)
            ->count();

        $usersPercentageChange = $lastMonthUsers > 0
            ? round((($currentUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1)
            : 0;

        // ==================== SHIFT FILL RATE ====================
        // Current month
        $currentShifts = Shift::with('claimShift')
            ->whereHas('claimShift')
            ->whereDate('date', '>=', $currentMonthStart)
            ->whereDate('date', '<=', $currentMonthEnd)
            ->get()
            ->groupBy('status');

        $currentTotalShifts = $currentShifts->flatten()->count();
        $currentCompletedShifts = $currentShifts->get(5, collect())->count() + $currentShifts->get(6, collect())->count();
        $currentFillRate = $currentTotalShifts > 0 ? round(($currentCompletedShifts / $currentTotalShifts) * 100, 1) : 0;

        // Last month
        $lastMonthShifts = Shift::with('claimShift')
            ->whereHas('claimShift')
            ->whereDate('date', '>=', $lastMonthStart)
            ->whereDate('date', '<=', $lastMonthEnd)
            ->get()
            ->groupBy('status');

        $lastMonthTotalShifts = $lastMonthShifts->flatten()->count();
        $lastMonthCompletedShifts = $lastMonthShifts->get(5, collect())->count() + $lastMonthShifts->get(6, collect())->count();
        $lastMonthFillRate = $lastMonthTotalShifts > 0 ? round(($lastMonthCompletedShifts / $lastMonthTotalShifts) * 100, 1) : 0;

        $fillRatePercentageChange = $currentFillRate - $lastMonthFillRate;

        // ==================== CANCELLATION RATE ====================
        // Current month
        $currentCancelledShifts = Shift::with('claimShift')
            ->whereHas('claimShift')
            ->where('status', -1)
            ->whereDate('date', '>=', $currentMonthStart)
            ->whereDate('date', '<=', $currentMonthEnd)
            ->count();

        $currentAllShifts = Shift::with('claimShift')
            ->whereHas('claimShift')
            ->whereDate('date', '>=', $currentMonthStart)
            ->whereDate('date', '<=', $currentMonthEnd)
            ->count();

        $currentCancellationRate = $currentAllShifts > 0 ? round(($currentCancelledShifts / $currentAllShifts) * 100, 1) : 0;

        // Last month
        $lastMonthCancelledShifts = Shift::with('claimShift')
            ->whereHas('claimShift')
            ->where('status', -1)
            ->whereDate('date', '>=', $lastMonthStart)
            ->whereDate('date', '<=', $lastMonthEnd)
            ->count();

        $lastMonthAllShifts = Shift::with('claimShift')
            ->whereHas('claimShift')
            ->whereDate('date', '>=', $lastMonthStart)
            ->whereDate('date', '<=', $lastMonthEnd)
            ->count();

        $lastMonthCancellationRate = $lastMonthAllShifts > 0 ? round(($lastMonthCancelledShifts / $lastMonthAllShifts) * 100, 1) : 0;

        $cancellationRatePercentageChange = $currentCancellationRate - $lastMonthCancellationRate;

        // ==================== TOTAL REVENUE ====================
        // Current month
        $currentRevenue = Payment::where('status', 'completed')
            ->whereDate('created_at', '>=', $currentMonthStart)
            ->whereDate('created_at', '<=', $currentMonthEnd)
            ->sum('amount');

        // Last month
        $lastMonthRevenue = Payment::where('status', 'completed')
            ->whereDate('created_at', '>=', $lastMonthStart)
            ->whereDate('created_at', '<=', $lastMonthEnd)
            ->sum('amount');

        $revenuePercentageChange = $lastMonthRevenue > 0
            ? round((($currentRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        // Format revenue (e.g., $602K, $1.2M)
        $formattedRevenue = $this->formatRevenue($currentRevenue);

        // ==================== USER GROWTH CHART DATA ====================
        $chartData = $this->getUserGrowthChartData();

        // ==================== FACILITY CHART DATA ====================
        $facilityChartData = $this->getFacilityChartData();

        // ==================== KEY TAKEAWAYS DATA ====================
        $keyTakeaways = $this->generateKeyTakeaways(
            $usersPercentageChange,
            $currentFillRate,
            $currentCancellationRate,
            $cancellationRatePercentageChange,
            $revenuePercentageChange,
            $facilityChartData
        );

        return view('pages.admin.reports', compact(
            'currentUsers',
            'usersPercentageChange',
            'currentFillRate',
            'fillRatePercentageChange',
            'currentCancellationRate',
            'cancellationRatePercentageChange',
            'formattedRevenue',
            'revenuePercentageChange',
            'chartData',
            'facilityChartData',
            'keyTakeaways'
        ));
    }

    // Generate key takeaways based on data
    private function generateKeyTakeaways($usersChange, $fillRate, $cancellationRate, $cancellationChange, $revenueChange, $facilityData)
    {
        $takeaways = [];

        // Takeaway 1: User growth
        $takeaways[] = [
            'type' => 'users',
            'color' => 'blue',
            'icon' => 'check',
            'text' => 'User base grew by <span class="font-semibold">' . abs($usersChange) . '%</span> this month, with consistent new user acquisition'
        ];

        // Takeaway 2: Shift fill rate
        $fillRateStatus = $fillRate >= 90 ? 'strong' : ($fillRate >= 70 ? 'moderate' : 'weak');
        $takeaways[] = [
            'type' => 'fillrate',
            'color' => 'green',
            'icon' => 'check',
            'text' => 'Shift fill rate remains ' . $fillRateStatus . ' at <span class="font-semibold">' . $fillRate . '%</span>, indicating ' . ($fillRate >= 90 ? 'high' : 'moderate') . ' platform utilization'
        ];

        // Takeaway 3: Cancellation rate
        $cancellationTrend = $cancellationChange <= 0 ? 'decreased' : 'increased';
        $cancellationAction = $cancellationChange <= 0 ? 'improved' : 'declined';
        $takeaways[] = [
            'type' => 'cancellation',
            'color' => 'orange',
            'icon' => 'check',
            'text' => 'Cancellation rate ' . $cancellationTrend . ' to <span class="font-semibold">' . $cancellationRate . '%</span>, showing ' . $cancellationAction . ' commitment from both parties'
        ];

        // Takeaway 4: Revenue
        $revenueStatus = $revenueChange >= 15 ? 'significantly' : ($revenueChange >= 5 ? 'moderately' : 'slightly');
        $takeaways[] = [
            'type' => 'revenue',
            'color' => 'purple',
            'icon' => 'check',
            'text' => 'Revenue ' . $revenueStatus . ' increased by <span class="font-semibold">' . abs($revenueChange) . '%</span> compared to last month, driven by increased facility usage'
        ];

        // Takeaway 5: Top facilities
        if (count($facilityData['labels']) > 0) {
            // Get top 2 facilities by shifts posted
            $facilityStats = [];
            for ($i = 0; $i < count($facilityData['labels']); $i++) {
                $facilityStats[] = [
                    'name' => $facilityData['labels'][$i],
                    'shifts' => $facilityData['shiftsPosted'][$i]
                ];
            }
            usort($facilityStats, function ($a, $b) {
                return $b['shifts'] <=> $a['shifts'];
            });

            $topFacilities = array_slice($facilityStats, 0, 2);
            $topNames = implode(' and ', array_map(function ($f) {
                return $f['name']; }, $topFacilities));

            $takeaways[] = [
                'type' => 'facilities',
                'color' => 'indigo',
                'icon' => 'check',
                'text' => $topNames . ' are the most active facilities'
            ];
        }

        return $takeaways;
    }

    // Helper method to format revenue
    private function formatRevenue($amount)
    {
        if ($amount >= 1000000) {
            return '$' . round($amount / 1000000, 1) . 'M';
        } elseif ($amount >= 1000) {
            return '$' . round($amount / 1000, 0) . 'K';
        }
        return '$' . $amount;
    }

    private function getUserGrowthChartData()
    {
        $months = [];
        $totalUsersData = [];
        $newUsersData = [];
        $previousMonthTotal = 0;

        // Get data for last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->startOfMonth();
            $monthEnd = $date->endOfMonth();
            $monthLabel = $date->format('M');

            // Total users up to end of this month
            $totalUsers = User::whereDate('created_at', '<=', $monthEnd)->count();
            $totalUsersData[] = $totalUsers;

            // New users in this month = total users this month - total users last month
            $newUsers = $totalUsers - $previousMonthTotal;
            $newUsersData[] = max(0, $newUsers);  // Ensure no negative values

            $previousMonthTotal = $totalUsers;
            $months[] = $monthLabel;
        }

        return [
            'labels' => $months,
            'totalUsers' => $totalUsersData,
            'newUsers' => $newUsersData,
            'maxValue' => max($totalUsersData) + 100  // Add padding for chart max
        ];
    }

    private function getFacilityChartData()
    {
        // Get all facilities (users with facility_mode role)
        $facilities = User::where('role', 'facility_mode')
            ->select('id', 'full_name')
            ->get();

        $labels = [];
        $shiftsPostedData = [];
        $shiftsFilledData = [];
        $shiftsCancelledData = [];

        foreach ($facilities as $facility) {
            $labels[] = $facility->full_name;

            // Shifts Posted - Total shifts created by this facility
            $shiftsPosted = Shift::where('user_id', $facility->id)->count();
            $shiftsPostedData[] = $shiftsPosted;

            // Shifts Filled - Shifts that have claim shifts (status 5 and 6 are completed)
            $shiftsFilled = Shift::where('user_id', $facility->id)
                ->whereHas('claimShift')
                ->whereIn('status', [5, 6])  // Assuming 5 & 6 are completed statuses
                ->count();
            $shiftsFilledData[] = $shiftsFilled;

            // Shifts Cancelled - Shifts with status -1 (cancelled)
            $shiftsCancelled = Shift::where('user_id', $facility->id)
                ->where('status', -1)
                ->count();
            $shiftsCancelledData[] = $shiftsCancelled;
        }

        // Calculate max value for Y-axis scaling
        $allData = array_merge($shiftsPostedData, $shiftsFilledData, $shiftsCancelledData);
        $maxValue = count($allData) > 0 ? max($allData) + 10 : 60;

        return [
            'labels' => $labels,
            'shiftsPosted' => $shiftsPostedData,
            'shiftsFilled' => $shiftsFilledData,
            'shiftsCancelled' => $shiftsCancelledData,
            'maxValue' => $maxValue
        ];
    }


}
