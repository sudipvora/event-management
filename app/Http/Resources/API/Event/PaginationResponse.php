<?php

namespace App\Http\Resources\API\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginationResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'current_page' => $this->currentPage(),
            'has_more_page' => $this->hasMorePages(),
            'total' => $this->total(),
            'last_page' => $this->lastPage(),
            'next_page_url' => $this->nextPageUrl(),
            'prev_page_url' => $this->previousPageUrl(),
            'data' => ListResponse::collection( $this->items() ),
        ];
    }
}
