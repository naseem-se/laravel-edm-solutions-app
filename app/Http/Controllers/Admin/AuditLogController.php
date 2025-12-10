<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = Activity::with('causer')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $today = Carbon::today();

        // 1️⃣ Total actions today
        $totalActionsToday = Activity::whereDate('created_at', $today)->count();

        // 2️⃣ Logins today
        $logins = Activity::whereDate('created_at', $today)
            ->where('description', 'LIKE', '%login%')
            ->orWhere('log_name','login')
            ->count();

        // 3️⃣ Edits today
        $edits = Activity::whereDate('created_at', $today)
            ->where('description', 'LIKE', '%update%')
            ->count();

        // 4️⃣ Deletions today
        $deletions = Activity::whereDate('created_at', $today)
            ->where('description', 'LIKE', '%delete%')
            ->count();

        return view('pages.admin.audit-log', compact('logs','totalActionsToday','logins','edits','deletions'));
    }
}
