<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
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
            'path'  => $this->download_url,
            'type'  => $this->type,
            'size'  => $this->size,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
