<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'chat_id'       => $this->chat_id,
            'id'            => $this->id,
            'body'          => $this->body,
            'seen'          => $this->seen,
            'user_id'       => $this->user_id,
            'attachments'   => AttachmentResource::collection($this->attachments),
            'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
