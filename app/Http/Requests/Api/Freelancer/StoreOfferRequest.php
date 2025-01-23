<?php

namespace App\Http\Requests\Api\Freelancer;

use App\Http\Requests\BaseFormRequest;

class StoreOfferRequest extends BaseFormRequest
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
            'freelancer_id' => 'required|exists:users,id',
            'service_id'    => 'required|exists:services,id',
            'client_id'     => 'required|exists:users,id',
            'price'         => 'required|numeric|min:0',
            'deadline'      => 'required|date|after:now|date_format:Y-m-d',
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.required'   => 'حدث خطأ ما',
            'client_id.required'    => 'حدث خطأ ما',
            'price.required'        => 'يجب تحديد سعر العرض',
            'price.numeric'         => 'يجب أن يكون سعر العرض رقم',
            'price.min'             => 'يجب أن يكون سعر العرض أكبر من 0',
            'deadline.required'     => 'يجب تحديد الموعد',
            'deadline.date'         => 'يجب أن يكون الموعد تاريخ',
            'deadline.after'        => 'يجب أن يكون الموعد بعد الآن',
            'deadline.date_format'  => 'يجب أن يكون الموعد بالصيغة Y-m-d',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'freelancer_id' => auth()->user()->id,
        ]);
    }
}
