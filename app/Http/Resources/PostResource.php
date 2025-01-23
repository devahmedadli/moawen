<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'content'       => $this->content,
            'image'         => $this->image,
            'read_time'     => $this->read_time,
            'user'          => $this->whenLoaded('user', fn() => [
                'id'        => $this->user->id,
                'name'      => $this->user->name,
                'image'     => $this->user->image,
            ]),
            'comments'      => $this->whenLoaded('comments', fn() =>
            [
                'id'        => $this->id,
                'content'   => $this->content,
                'created_at' => $this->created_at->format('Y-m-d h:i:s A'),
            ]),
            'likes_count'   => $this->likes_count,
            'created_at'    => $this->created_at->format('Y-m-d h:i:s A'),
        ];
    }
}
