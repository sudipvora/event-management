<?php

namespace App\Http\Resources\API\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResponse extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'location' => $this->location,
            'capacity' => $this->capacity ?? 0,
            'total_bookings' => $this->bookings_count,
            'is_full' => $this->isBookingFull(),
            'remaining_seats' => $this->getRemainingSeats(),
            'event_date' => $this->event_date?->format('Y-m-d'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
