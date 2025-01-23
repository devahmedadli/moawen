<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            'url'           => $this->url,
            'is_public'     => $this->is_public,
            'primary_image' => $this->when($request->with == null, function () {
                return PortfolioImageResource::make($this->images()->first());
            }),
            'images'        => $this->whenLoaded('images', function () {
                return PortfolioImageResource::collection($this->images);
            }),
        ];
    }
}
