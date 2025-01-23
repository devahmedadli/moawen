<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imageInfo = $request->get('imageInfo') ?? false;
        return [
            'id'            => $this->id,
            'path'          => $this->path,
            'original_name' => $this->when($imageInfo, fn() => $this->original_name),
            'mime_type'     => $this->when($imageInfo, fn() => $this->mime_type),
            'size'          => $this->when($imageInfo, fn() => $this->size),
            'order'         => $this->when($imageInfo, fn() => $this->order),
            'is_primary'    => $this->when($imageInfo, fn() => $this->is_primary),
        ];
    }
}
