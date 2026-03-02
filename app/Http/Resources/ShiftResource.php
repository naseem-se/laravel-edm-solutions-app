<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class ShiftResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $user = User::find($this->user_id);

        return [
            'id' => $this->id,
            'special_instruction' => $this->special_instruction,
            'start_time' => \Carbon\Carbon::parse($this->start_time)->format('g:i A'),
            'end_time' => \Carbon\Carbon::parse($this->end_time)->format('g:i A'),
            'per_hour' => $this->pay_per_hour . '$',
            'location' => $this->location,
            'title' => $this->title,
            'license_type' => $this->license_type,
            'status' => $this->status,
            'is_emergency' => $this->is_emergency,
            'status_text' => $this->getStatusText($this->status),
            'is_claimed' => $this->status == 3 ? 'Claimed' : 'Claim',
            'date' => $this->date,
            'facility_id' => $user->id,
            'facility_name' => $user->facility_name,
            'address'=>$user->address,
            'firebase_uid'=>$user->firebase_uid,
            'claimed_by' => $this->claimShift ? new UserResource($this->claimShift->user) : null,
        ];
    }

    private function getStatusText($status)
    {
        return match ($status) {
            0 => 'Pending',
            1 => 'Opened',
            2 => 'Pending Approval',
            3 => 'Confirmed',
            4 => 'In Progress',
            5 => 'Completed',
            6 => 'Paid',
            -1 => 'Cancelled',
            default => 'Unknown',
        };
    }
}
