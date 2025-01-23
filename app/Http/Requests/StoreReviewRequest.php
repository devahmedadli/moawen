<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Order;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if the user has purchased the service
        $order = Order::where([
            'buyer_id'    => auth()->id(),
            'id'          => $this->order_id,
            'status'      => 'completed'
        ])->first();

        return (bool) $order;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:1000',
        ];
    }
}
