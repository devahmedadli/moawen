<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed> 
     * 
     */

    public function toArray(Request $request): array
    {

        $allInfo = $request->get('all_info') == 'true';

        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'price'         => (int) $this->price,
            'delivery_time' => $this->when($allInfo, $this->delivery_time),
            'response_time' => $this->when($allInfo, $this->response_time),
            'service_level' => $this->when($allInfo, $this->service_level),
            'thumbnail'     => $this->thumbnail,
            'tags'          => $this->when($allInfo, is_string($this->tags) ? json_decode($this->tags) : $this->tags),
            'status'        => $this->status,
            'rating'        => $this->average_rating,
            'views'         => $this->when($allInfo, $this->views),
            'created_at'    => $this->when($allInfo, $this->created_at),
            'updated_at'    => $this->when($allInfo, $this->updated_at),
            'links'         => [
                'self' => route('services.show', $this->id),
            ],
            'category'      => $this->whenLoaded('category', fn() => [
                'id'        => $this->category->id,
                'name'      => $this->category->name,
            ]),
            'freelancer'    => UserResource::make($this->whenLoaded('freelancer')),
            'offers'        => $this->whenLoaded('offers', function () {
                return $this->offers->map(function ($offer) {
                    return [
                        'id' => $offer->id,
                        'price' => $offer->price,
                        'status' => $offer->status,
                        'created_at' => $offer->created_at->format('Y-m-d h:i:s A'),
                    ];
                });
            }),
            'reviews'       => ReviewCollection::collection($this->when($request->get('reviews') == 'true', $this->whenLoaded('reviews'))),
            'orders_count'  => empty($this->orders) ? 0 : $this->orders->count(),
            'reviews_count' => empty($this->reviews) ? 0 : $this->reviews->count(),
            'offers_count'  => empty($this->offers) ? 0 : $this->offers->count(),
        ];
    }
}
