<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClaimShift;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;

class TimesheetController extends Controller
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
            })
            ->whereIn('status',[5,6])
            ->latest()->paginate(10);


        return view('pages.admin.time-sheet', compact('shifts'));
    }
}
