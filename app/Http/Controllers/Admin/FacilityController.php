<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $users = User::withCount('activeShifts')->where('role', 'facility_mode')
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('full_name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('phone_number', 'like', '%' . $request->search . '%');
                });
            })
            ->where('is_verified',true)
            ->latest()
            ->paginate(10);
        $total_pending = $users->count();
        return view('pages.admin.facilities', compact('users','total_pending'));
    }

    public function pendingFacilities(Request $request){
        $users = User::withCount('activeShifts')->where('role', 'facility_mode')
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('full_name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('phone_number', 'like', '%' . $request->search . '%');
                });
            })
            ->where('is_verified',false)
            ->latest()
            ->paginate(10);
        $total_pending = $users->count();
        return view('pages.admin.pending-facilities',compact('users','total_pending'));
    }

    public function approveFacilty(Request $request,$id){
        try {
            $user = User::where('role', 'facility_mode')->findOrFail($id);
            $user->is_verified = true;
            $user->update();

            return redirect()->route('admin.facilities.index')
                ->with('success', 'Facility approved successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function rejectFacilty(Request $request,$id){
        try {
            $user = User::where('role', 'facility_mode')->findOrFail($id);
            $user->delete();

            return redirect()->route('admin.facilities.index')
                ->with('success', 'Facility rejected successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::where('role', 'facility_mode')->findOrFail($id);
            $user->delete();

            return redirect()->route('admin.facilities.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
