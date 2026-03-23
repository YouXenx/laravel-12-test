<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\HeadOfFamilyResource;

class EventParticipantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event' => new EventResource($this->event),
            'head_of_family' => new HeadOfFamilyResource($this->headOfFamily),
            'quantity' => $this->quantity,
            'total_price' => (float) $this->total_price,
            'payment_status' => $this->payment_status
        ];
    }
}
