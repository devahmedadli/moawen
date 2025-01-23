<?php

namespace App\Http\Requests\Api\Client;

use App\Http\Requests\BaseFormRequest;

class StoreReviewRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id'  => 'required|exists:orders,id',
            'user_id'   => 'required|exists:users,id',
            'comment'   => 'required|string|max:255',
            'rating'    => 'required|integer|min:1|max:5',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'حدث خطأ ما.',
            'order_id.exists'   => 'حدث خطأ ما.',
            'comment.required'  => 'يرجى اضافة التعليق.',
            'rating.required'   => 'يرجى اضافة التقييم.',
            'rating.integer'    => 'يرجى اضافة التقييم بالأرقام.',
            'rating.min'        => 'يرجى اضافة التقييم بين 1 و 5.',
            'rating.max'        => 'يرجى اضافة التقييم بين 1 و 5.',
        ];
    }


    protected function prepareForValidation()
    {
        $this->merge([
            'user_id'   => auth()->id(),
        ]);
    }
}
