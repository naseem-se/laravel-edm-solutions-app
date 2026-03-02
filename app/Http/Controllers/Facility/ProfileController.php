<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\FacilityDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    public function getProfile()
    {
        $facility = auth()->user();

        return response()->json([
            'success' => true,
            'data' => $facility,
        ]);
    }
    public function updateProfile(Request $request)
{
    $user = auth()->user();

    // Validate request
    $validatedData = $request->validate([
        'full_name'     => 'required|string|max:255',
        'email'         => 'required|email|unique:users,email,' . $user->id,
        'facility_name' => 'required|string|max:255',
        'address'       => 'required|string|max:500',
        'phone_number'  => 'required|string|max:20',
        'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {

        // Delete old image if exists
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        // Store new image
        $validatedData['image'] = $request
            ->file('image')
            ->store('profile', 'public');
    }

    // Update user profile
    $user->update($validatedData);

    return response()->json([
        'success' => true,
        'message' => 'Profile updated successfully',
        'data'    => $user,
    ]);
}


    public function getFacilityDetail()
    {
        $user = auth()->user();

        return response()->json([
            'success' => true,
            'data' => $user->facilityDetail(),
        ]);
    }

    public function updateFacilityDetail(Request $request)
    {
        $user = auth()->user();

        // Validate the incoming request data
        $validatedData = $request->validate([
            'type' => 'required|string|max:100',
            'license_number' => 'required|string|max:100',
            'accreditation' => 'required|string|max:255',
            'tax_id' => 'required|string|max:100',
            'total_beds' => 'required|integer',
            'total_dept' => 'required|integer',
            'description' => 'required|string|max:1000',
        ]);

        try {
            // Update or create the facility detail
            $facilityDetail = FacilityDetail::where('facility_id', $user->id)->first();

            $validatedData['facility_id'] = $user->id;
            if ($facilityDetail) {
                $facilityDetail->update($validatedData);
            } else {
                FacilityDetail::create($validatedData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Facility details updated successfully',
                'data' => $facilityDetail,
            ]);
        } catch (\Exception $e) {
           return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
           ]);
        }


    }
}
