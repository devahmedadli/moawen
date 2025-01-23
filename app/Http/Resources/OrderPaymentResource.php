<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'order_id'          => $this->order_id,
            'user_id'           => $this->user_id,
            'amount'            => $this->amount,
            'payment_method'    => $this->payment_method,
            'payment_status'    => $this->payment_status,
            'payment_id'        => $this->payment_id,
            'status'            => $this->status,
            'created_at'        => $this->created_at->format('Y-m-d h:i:s A'),
            'order'             => $this->whenLoaded('order', new OrderResource($this->order)),
        ];
    }
}
