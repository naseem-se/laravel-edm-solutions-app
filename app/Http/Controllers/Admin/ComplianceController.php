<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class ComplianceController extends Controller
{
    public function index(Request $request)
    {
        $expiredCount = Document::expired()->count();
        $expiringSoonCount = Document::expiringsoon()->count();

        $documents = Document::with('user') // eager load the user
                    ->when($request->search, function ($query) use ($request) {
                        $query->whereHas('user', function ($q) use ($request) {
                            $q->where('full_name', 'like', '%' . $request->search . '%')
                            ->orWhere('email', 'like', '%' . $request->search . '%')
                            ->orWhere('phone_number', 'like', '%' . $request->search . '%');
                        });
                    })
                    ->whereHas('user', function ($q) {
                        $q->where('is_verified', true);
                    })
                    ->where('status',1)
                    ->latest()
                    ->paginate(10);
        $total_pending = Document::where('status',0)->count();
        return view('pages.admin.compliance', compact('documents','total_pending','expiredCount','expiringSoonCount'));
    }

    public function pendingCompliances(Request $request)
    {
        $expiredCount = Document::expired()->count();
        $expiringSoonCount = Document::expiringsoon()->count();

        $documents = Document::with('user') // eager load the user
                    ->when($request->search, function ($query) use ($request) {
                        $query->whereHas('user', function ($q) use ($request) {
                            $q->where('full_name', 'like', '%' . $request->search . '%')
                            ->orWhere('email', 'like', '%' . $request->search . '%')
                            ->orWhere('phone_number', 'like', '%' . $request->search . '%');
                        });
                    })
                    ->whereHas('user', function ($q) {
                        $q->where('is_verified', true);
                    })
                    ->where('status',0)
                    ->latest()
                    ->paginate(10);
        $total_pending = $documents->count();
        return view('pages.admin.pending-compliance', compact('documents','total_pending','expiredCount','expiringSoonCount'));
    }


    public function approveCompliance(Request $request,$id){
        try {
            $user = Document::findOrFail($id);
            $user->status = 1;
            $user->update();

            return redirect()->route('admin.compliances.index')
                ->with('success', 'Compliance approved successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function rejectCompliance(Request $request,$id){
        try {
            $user = Document::findOrFail($id);
            Storage::disk('public')->delete($user->document);
            $user->delete();

            return redirect()->route('admin.compliances.pending')
                ->with('success', 'Compliance rejected successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function expiringSoon(){
        $documents = Document::whereRaw('DATE_ADD(created_at, INTERVAL 1 YEAR) > NOW()')
                                      ->whereRaw('DATE_ADD(created_at, INTERVAL 1 YEAR) < DATE_ADD(NOW(), INTERVAL 50 DAY)')
                                      ->orderBy('created_at', 'asc')
                                      ->paginate(15);
        
        $expiredCount = Document::expired()->count();
        $expiringSoonCount = Document::expiringsoon()->count();
        $total_pending = Document::where('status',0)->count();

        return view('pages.admin.expire-soon',compact('documents','expiredCount','expiringSoonCount','total_pending'));
    }
    public function expired(){
        $documents = Document::whereRaw('DATE_ADD(created_at, INTERVAL 1 YEAR) < NOW()')
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

        $expiredCount = Document::expired()->count();
        $expiringSoonCount = Document::expiringsoon()->count();
        $total_pending = Document::where('status',0)->count();
                                      
        return view('pages.admin.expired',compact('documents','expiredCount','expiringSoonCount','total_pending'));
    }

}
