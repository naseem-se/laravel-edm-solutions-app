<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use App\Models\ShiftInvitation;
use App\Models\Shift;
use App\Models\ClaimShift;
use App\Models\User;
use App\Mail\ShiftInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ShiftInvitationController extends Controller
{
    public function sendInvitation(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'shift_id' => 'required|exists:shifts,id',
                'worker_id' => 'required|exists:users,id',
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validation->errors()->first(),
                ], 400);
            }

            $facility = auth()->user();
            $shift = Shift::findOrFail($request->shift_id);
            $worker = User::findOrFail($request->worker_id);

            if ($shift->user_id !== $facility->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            $existingClaim = ClaimShift::where('user_id', $worker->id)
                ->whereDate('created_at', $shift->date)
                ->where(function ($query) use ($shift) {
                    $query->whereBetween('start_time', [$shift->start_time, $shift->end_time])
                        ->orWhereBetween('end_time', [$shift->start_time, $shift->end_time]);
                })
                ->exists();

            if ($existingClaim) {
                return response()->json([
                    'success' => false,
                    'message' => 'Worker already has shift at this time',
                ], 400);
            }

            $existingInvitation = ShiftInvitation::where('shift_id', $shift->id)
                ->where('worker_id', $worker->id)
                ->where('status', 'pending')
                ->exists();

            if ($existingInvitation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invitation already sent',
                ], 400);
            }

            DB::beginTransaction();

            try {
                // Generate unique token
                $token = Str::random(32);

                $invitation = ShiftInvitation::create([
                    'shift_id' => $shift->id,
                    'worker_id' => $worker->id,
                    'facility_id' => $facility->id,
                    'status' => 'pending',
                    'expires_at' => now()->addDays(3),
                    'token' => $token,
                ]);

                // Create web link
                $webLink = url('/invitations/' . $token);

                Mail::to($worker->email)->send(new ShiftInvitationMail(
                    shift: $shift,
                    facility: $facility,
                    worker: $worker,
                    webLink: $webLink
                ));

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Invitation sent',
                    'data' => [
                        'invitation_id' => $invitation->id,
                        'worker_name' => $worker->name,
                    ],
                ], 201);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function viewInvitation($token)
    {
        try {
            $invitation = ShiftInvitation::where('token', $token)
                ->where('status', 'pending')
                ->where('expires_at', '>', now())
                ->with('shift', 'facility', 'worker')
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'invitation_id' => $invitation->id,
                    'token' => $token,
                    'worker_id' => $invitation->worker_id,
                    'worker_email' => $invitation->worker->email,
                    'facility_name' => $invitation->facility->name,
                    'shift_date' => $invitation->shift->start_date->format('M d, Y'),
                    'shift_time' => $invitation->shift->start_time . ' - ' . $invitation->shift->end_time,
                    'shift_title' => $invitation->shift->title,
                    'expires_at' => $invitation->expires_at->format('M d, Y'),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired invitation',
            ], 404);
        }
    }

    public function acceptInvitation(Request $request, $token)
    {
        try {
            $invitation = ShiftInvitation::where('token', $token)
                ->where('status', 'pending')
                ->firstOrFail();

            if ($invitation->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Already processed',
                ], 400);
            }

            $existingClaim = ClaimShift::where('user_id', $invitation->worker_id)
                ->whereDate('created_at', $invitation->shift->date)
                ->where(function ($query) use ($invitation) {
                    $query->whereBetween('start_time', [$invitation->shift->start_time, $invitation->shift->end_time])
                        ->orWhereBetween('end_time', [$invitation->shift->start_time, $invitation->shift->end_time]);
                })
                ->exists();

            if ($existingClaim) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have another shift at this time',
                ], 400);
            }

            DB::beginTransaction();

            try {
                $invitation->update(['status' => 'accepted']);

                $shift = Shift::findOrFail($invitation->shift_id);

                $shift->claimShift()->create([
                    'user_id' => $invitation->worker_id,
                    'start_time' => $shift->start_time,
                    'end_time' => $shift->end_time,
                ]);

                $shift->update([
                    'status' => 2, // Confirmed
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Shift accepted!',
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function rejectInvitation($token)
    {
        try {
            $invitation = ShiftInvitation::where('token', $token)
                ->where('status', 'pending')
                ->firstOrFail();

            $invitation->update(['status' => 'rejected']);

            $invitation->delete();

            return response()->json([
                'success' => true,
                'message' => 'Invitation rejected',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($invitationToken, Request $request)
    {
        try {
            $invitation = ShiftInvitation::where('token', $invitationToken)
                ->where('status', 'pending')
                ->where('expires_at', '>', now())
                ->with(['shift', 'facility', 'worker'])
                ->firstOrFail();

            $user = $invitation->worker;

            // 🔐 Single-login token strategy (recommended)
            $user->tokens()->where('name', 'auth_token')->delete();

            $userToken = $user->createToken('auth_token')->plainTextToken;

            return view('invitations.show', [
                'invitation' => $invitation,
                'userToken' => $userToken,
                'token' => $invitationToken,
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return view('invitations.expired');
        }
    }
}
