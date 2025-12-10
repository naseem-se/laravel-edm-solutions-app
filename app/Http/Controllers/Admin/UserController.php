<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('role', 'worker_mode')
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('full_name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('phone_number', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->status == 'active', function ($query) {
                $query->whereNotNull('email_verified_at');
            })
            ->when($request->status == 'inactive', function ($query) {
                $query->whereNull('email_verified_at');
            })
            ->latest()
            ->paginate(10);
        return view('pages.admin.user', compact('users'));
    }

    public function destroy($id)
    {
        try {
            $user = User::where('role', 'facility_mode')->findOrFail($id);
            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
