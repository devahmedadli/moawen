<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'buyer_id'      => $this->buyer_id,
            'seller_id'     => $this->seller_id,
            'service_id'    => $this->service_id,
            'deadline'      => $this->deadline,
            'price'         => $this->price,
            'rating_given'  => $this->rating_given,
            'status'        => $this->status,
            'created_at'    => $this->created_at->format('Y-m-d h:i:s A'),
            'payment'       => $this->whenLoaded('payment', new OrderPaymentResource($this->payment)),
            'client'        => $this->whenLoaded('client', fn() => [
                'id'    => $this->client->id,
                'name'  => $this->client->full_name,
            ]),
            'freelancer'    => $this->whenLoaded('freelancer', fn() => [
                'id'    => $this->freelancer->id,
                'name'  => $this->freelancer->full_name,
            ]),
            'service'       => $this->whenLoaded('service', fn() => [
                'id'    => $this->service->id,
                'name'  => $this->service->name,
                'thumbnail' => $this->service->thumbnail,
                'category'  => $this->whenLoaded('category', fn() => [
                    'id'    => $this->category->id,
                    'name'  => $this->category->name,
                ]),
            ]),
            'review'        => $this->whenLoaded('review', fn() => [
                'id'        => $this->review->id,
                'rating'    => $this->review->rating,
                'comment'   => $this->review->comment,
            ]),
        ];
    }
}
