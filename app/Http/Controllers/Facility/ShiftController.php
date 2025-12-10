<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateShiftRequest;
use App\Http\Requests\UpdateShiftRequest;
use App\Http\Resources\ShiftResource;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    public function getShift()
    {
        try {
            // Fetch shifts for the given date
            $shifts = Shift::where('user_id', auth()->id())->with('claimShift')
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => ShiftResource::collection($shifts),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function createShift(CreateShiftRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $shift = Shift::create([
                'user_id' => auth()->id(),
                'date' => $validated['date'],
                'pay_per_hour' => $validated['pay_per_hour'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'title' => $validated['title'],
                'license_type' => $validated['license_type'],
                'special_instruction' => $validated['special_instruction'],
                'location' => $validated['location'],
                'is_emergency' => $validated['is_emergency'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Shift created successfully.',
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create shift: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function updateShift(UpdateShiftRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            $shift = Shift::where('user_id', $user->id)->findOrFail($id);

            $shift->update($request->only([
                'date',
                'pay_per_hour',
                'start_time',
                'end_time',
                'title',
                'license_type',
                'special_instruction',
                'location'
            ]));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Shift updated successfully.',
                'data' => $shift,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update shift: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function deleteShift($id)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            // Facility can only delete their own shifts
            $shift = Shift::where('user_id', $user->id)->findOrFail($id);

            $shift->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Shift deleted successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Shift not found or not owned by you.'
            ], 404);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete shift: ' . $th->getMessage()
            ], 500);
        }
    }
}
