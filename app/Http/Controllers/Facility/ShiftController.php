<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateShiftRequest;
use App\Http\Requests\UpdateShiftRequest;
use App\Http\Resources\ShiftResource;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
                'is_emergency' => $validated['is_emergency'] ?? false,
                'status' => 1, // Opened
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
    
    public function createBulkShift(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'shifts' => 'required|array|min:1',

            'shifts.*.date' => 'required|date',
            'shifts.*.pay_per_hour' => 'required|numeric|min:0',
            'shifts.*.start_time' => 'required|date_format:H:i',
            'shifts.*.end_time' => 'required|date_format:H:i|after:shifts.*.start_time',
            'shifts.*.title' => 'required|string|max:255',
            'shifts.*.license_type' => 'required|string|max:255',
            'shifts.*.special_instruction' => 'nullable|string',
            'shifts.*.location' => 'required|string|max:255',
            'shifts.*.is_emergency' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            foreach ($request->shifts as $shiftData) {
                Shift::create([
                    'user_id' => auth()->id(),
                    'date' => $shiftData['date'],
                    'pay_per_hour' => $shiftData['pay_per_hour'],
                    'start_time' => $shiftData['start_time'],
                    'end_time' => $shiftData['end_time'],
                    'title' => $shiftData['title'],
                    'license_type' => $shiftData['license_type'],
                    'special_instruction' => $shiftData['special_instruction'] ?? null,
                    'location' => $shiftData['location'],
                    'is_emergency' => $shiftData['is_emergency'] ?? false,
                    'status' => 1, // Open
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bulk shifts created successfully.',
            ], 201);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create bulk shifts.',
                'error' => $th->getMessage(),
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
                'location',
                'is_emergency',
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

    public function rejectShift($id)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            // Facility can only reject their own shifts
            $shift = Shift::where('user_id', $user->id)->findOrFail($id);

            $shift->status = 1; // Set status to opened
            $shift->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Shift rejected successfully.'
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
                'message' => 'Failed to reject shift: ' . $th->getMessage()
            ], 500);
        }
    }
}
