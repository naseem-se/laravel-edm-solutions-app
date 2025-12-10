<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        if (
            $checkIn && $checkOut &&
            $checkIn->format('H:i:s') !== '00:00:00' &&
            $checkOut->format('H:i:s') !== '00:00:00'
        ) {

            if ($checkOut->lessThan($checkIn)) {
                $checkOut->addDay();
            }

            $workedMinutes = $checkIn->diffInMinutes($checkOut);
            $workedHours = intdiv($workedMinutes, 60);
            $workedMins = $workedMinutes % 60;

            $workedTime = sprintf(' (Worked: %d hr %d min)', $workedHours, $workedMins);
        }

        $dutyDate = \Carbon\Carbon::parse($this->date)->format('M j, Y');
        $dutyStart = \Carbon\Carbon::parse($this->start_time)->format('H:i');
        $dutyEnd = \Carbon\Carbon::parse($this->end_time)->format('H:i');

        // Get facility name from location or use a default
        $facilityName = $this->location ?? 'St. Mary Care';

        // Get user's license info
        $licenseType = $this->license_type ?? 'RN';
        $userName = $this->user->name ?? 'Alexander Steve';

        // Assuming you have an expiration date field, adjust as needed
        $licenseExpiry = '2026-04-02'; // You may need to get this from user model

        return [
            'id' => $this->id,
            'title' => "Morning RN {$facilityName}",
            'user_name' => $userName,
            'license_info' => "License: {$licenseType} • Exp: {$licenseExpiry}",
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
        ];
    }
}
