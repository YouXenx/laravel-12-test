<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DevelopmentApplicantResource extends JsonResource
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
            'development' => optional($this->development) ? new DevelopmentResource($this->development) : null,
            'user' => optional($this->user) ? new UserResource($this->user) : null,
            'status' => $this->status,
        ];
    }
}
