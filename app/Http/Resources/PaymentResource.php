<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'amount' => $this->amount,
            'transfer_status' => $this->transfer_status,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('Y-m-d'),
            'due_date' => $this->created_at->addDays(10)->format('Y-m-d'),
            'total_hours' => $this->calculateHours($this->shift),
            'total_shifts' => (int) preg_replace('/\D+/', '', $this->description),

            // Recipient
            'recipient' => [
                'id' => $this->recipient?->id,
                'name' => $this->recipient?->full_name,
                'email' => $this->recipient?->email,
            ],

            // Shift
            'shift' => [
                'id' => $this->shift?->id,
                'title' => $this->shift?->title,
                'date' => $this->shift?->date,
                'pay_per_hour' => $this->shift?->pay_per_hour,
            ],
        ];
    }

    private function calculateHours($shift)
    {
        try {
            if ($shift->check_in && $shift->check_out) {
                $start = \Carbon\Carbon::parse($shift->check_in);
                $end = \Carbon\Carbon::parse($shift->check_out);
            } else {
                $start = \Carbon\Carbon::parse($shift->start_time);
                $end = \Carbon\Carbon::parse($shift->end_time);
            }

            if ($end->lessThan($start)) {
                $end->addDay();
            }

            return round($start->diffInMinutes($end) / 60, 1);
        } catch (\Exception $e) {
            return 0;
        }
    }
}
