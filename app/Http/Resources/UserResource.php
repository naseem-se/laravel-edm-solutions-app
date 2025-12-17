<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone_number,
            'address' => $this->address,
            'city' => $this->city,
            'zip' => $this->zip,
            'image'=> $this->image,
            'rating' => number_format($this->workerReviews()->avg('rating') ?? 0, 1),
            'credentials' => $this->documents()->pluck('type')->toArray(),
            'total_shifts' => $this->claimShifts()->count(),
        ];
    }
}
