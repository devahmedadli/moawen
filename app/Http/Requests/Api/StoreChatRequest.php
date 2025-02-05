<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseFormRequest;

class StoreChatRequest extends BaseFormRequest
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
            'from_id'   => ['required', 'exists:users,id'],
            'to_id'     => ['required', 'exists:users,id'],
            'order_id'  => ['nullable', 'exists:orders,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'from_id.required'  => 'حدث خطأ ما',
            'from_id.exists'    => 'حدث خطأ ما',
            'to_id.required'    => 'حدث خطأ ما',
            'to_id.exists'      => 'حدث خطأ ما',
            'order_id.exists'   => 'حدث خطأ ما',
        ];
    }

    public function prepareForValidation()
    {
        $this->replace([
            'from_id' => auth()->user()->id,
        ]);
    }
}
