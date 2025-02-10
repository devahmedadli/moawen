<?php

namespace App\Http\Requests\Api\Freelancer;

use App\Http\Requests\BaseFormRequest;

class StoreWithdrawalRequest extends BaseFormRequest
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
            'amount' => ['bail', 'required', 'numeric', 'min:0', function ($attribute, $value, $fail) {
                if ($value > auth()->user()->balance) {
                    $fail('لا يوجد رصيد كافي لإجراء هذا السحب');
                    return;
                }
            }],
            'method' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required'   => 'يجب عليك ادخال المبلغ المراد سحبه',
            'amount.numeric'    => 'يجب عليك ادخال رقم صالح للمبلغ',
            'amount.min'        => 'يجب عليك ادخال مبلغ صالح',
            'method.required'   => 'يجب عليك ادخال طريقة السحب',
            'method.string'     => 'يجب عليك ادخال طريقة سحب صالحة',
        ];
    }
}