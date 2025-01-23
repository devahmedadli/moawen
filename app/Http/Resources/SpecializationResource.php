<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecializationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'freelancers' => UserResource::collection($this->whenLoaded('freelancers')),
            'freelancers_count' => $this->whenCounted('freelancers', fn() => $this->freelancers_count),
            
        ];
    }
}
