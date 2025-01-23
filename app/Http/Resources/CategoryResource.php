<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'slug'      => $this->slug,
            'icon'      => $this->icon,
            'description' => $this->description,
            'services'  => ServiceResource::collection($this->whenLoaded('services')),
            'services_count' => $this->whenCounted('services', $this->services_count),
            'specializations'  => SpecializationResource::collection($this->whenLoaded('specializations')),
            'specializations_count' => $this->whenCounted('specializations', $this->specializations_count),
            'freelancers'  => UserResource::collection($this->whenLoaded('freelancers')),
            'freelancers_count' => $this->whenCounted('freelancers', $this->freelancers_count),
        ];
    }
}
