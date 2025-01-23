<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'user'      => $this->whenLoaded('user', fn() => [
                'id'        => $this->user->id,
                'name'      => $this->user->full_name,
                'image'     => $this->user->image,
            ]),
            'rating'    => $this->rating,
            'comment'   => $this->comment,
            'created_at' => $this->created_at->format('Y-m-d h:i:s A'),
            'order'   => $this->whenLoaded('order', fn() => [
                'id'        => $this->order->id,
                'price'     => $this->order->price,
                'service'   => $this->whenLoaded('service', fn() => [
                    'id'        => $this->order->service->id,
                    'name'      => $this->order->service->name,
                ]),
            ]),
        ];
    }
}
