<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FilledShiftResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $checkIn = $this->claimShift?->check_in ? \Carbon\Carbon::parse($this->claimShift->check_in) : null;
        $checkOut = $this->claimShift?->check_out ? \Carbon\Carbon::parse($this->claimShift->check_out) : null;

        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);

        // If end time is before start time, it means shift ended next day
        if ($end->lessThan($start)) {
            $end->addDay();
        }

        $totalHours = $start->diffInHours($end);

        // Calculate worked time if checked in and out
        
        $workedTime = '';
        $workedHours = 0;
        $workedMins = 0;
        $workedMinutes = 0;

        if (
            !empty($checkIn) &&
            !empty($checkOut) &&
            $checkIn->format('H:i:s') !== '00:00:00' &&
            $checkOut->format('H:i:s') !== '00:00:00'
        ) {
            // Clone to avoid mutating original objects
            $in = $checkIn->copy();
            $out = $checkOut->copy();

            // Handle overnight shift (e.g. 10 PM → 6 AM)
            if ($out->lessThan($in)) {
                $out->addDay();
            }

            $workedMinutes = $in->diffInMinutes($out);
            $workedHours = intdiv($workedMinutes, 60);
            $workedMins = $workedMinutes % 60;

            $workedTime = sprintf(' (Worked: %d hr %d min)', $workedHours, $workedMins);
        }

        $dutyDate = \Carbon\Carbon::parse($this->date)->format('M j, Y');
        $dutyStart = \Carbon\Carbon::parse($this->start_time)->format('H:i');
        $dutyEnd = \Carbon\Carbon::parse($this->end_time)->format('H:i');

        // Get facility name from location or use a default
        $facilityName = $this->location ?? 'Unknown';

        // Get user's license info
        $licenseType = $this->license_type ?? 'Unknown';
        $userName = $this->claimShift->user->full_name ?? 'Unknown';

        // Assuming you have an expiration date field, adjust as needed
        // $licenseExpiry = '2026-04-02'; // You may need to get this from user model
        
        // Null-safe pay_per_hour
        $payPerHour = $this->pay_per_hour ?? 0;

        // Final total pay (safe even if no check-in/out)
        $totalPay = ($payPerHour * $workedHours) + ($payPerHour * $workedMins / 60);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'worker_id' => $this->claimShift?->user_id,
            'user_name' => $userName,
            'image' =>  $this->user->image
                        ? Storage::url( $this->user->image)
                        : null,
            'license_info' => "License: {$licenseType}",
            'duty_time' => sprintf(
                '%s • %s — %s • %d Hr%s',
                $dutyDate,
                $dutyStart,
                $dutyEnd,
                $totalHours,
                $workedTime
            ),
            'result' => $this->status == 4 ? 'Awaiting' : ($this->status == 5 ? 'Completed' : 'Paid'),
            'special_instruction' => $this->special_instruction,
            'location' => $this->location,
            'pay_per_hour' => $this->pay_per_hour,
            'total_amount' => round($totalPay, 2),
        ];
    }
}
