<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'price'     => $this->price,
            'status'    => $this->status,
            'deadline'  => $this->deadline,
            'created_at' => $this->created_at->format('Y-m-d h:i:s A'),
            'service'   => $this->whenLoaded('service', function () {
                return [
                    'id' => $this->service->id,
                    'name' => $this->service->name,
                ];
            }),
            'client'    => $this->whenLoaded('client', function () {
                return [
                    'id' => $this->client->id,
                    'name' => $this->client->first_name . ' ' . $this->client->last_name,
                ];
            }),
            'freelancer' => $this->whenLoaded('freelancer', function () {
                return [
                    'id' => $this->freelancer->id,
                    'name' => $this->freelancer->first_name . ' ' . $this->freelancer->last_name,
                ];
            }),
        ];
    }
}
