<?php
// app/Http/Resources/Visitor/BoothResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BoothResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'booth_number' => $this->booth_number,
            'booth_type'   => $this->booth_type,

            // Safe optional chaining — hall may not be assigned yet
            'hall_name'    => $this->whenLoaded(
                'sector',
                fn () => $this->sector?->hall?->name
            ),
        ];
    }
}
