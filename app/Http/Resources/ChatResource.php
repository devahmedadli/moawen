<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $chatWith = $this->user_1_id == auth()->user()->id ? $this->user2 : $this->user1;
        return [
            // 'id'        => $this->id,
            'chat_with' => [
                'id'    => $chatWith->id,
                'name'  => $chatWith->full_name,
                'image' => $chatWith->image,
            ],
            'last_message_at' => $this->last_message_at,
            'messages'  => $this->when($request->all_messages == 'true', function () {
                return MessageResource::collection($this->messages);
            }),
        ];
    }
}

