<?php

namespace App\Http\Resources\API\Attendee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendeeSingleResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $this->loadCount('bookings');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'total_bookings' => $this->bookings_count ?? 0,
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
