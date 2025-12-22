<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShiftResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
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
