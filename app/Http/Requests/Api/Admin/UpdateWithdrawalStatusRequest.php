<?php

namespace App\Http\Requests\Api\Admin;

use App\Http\Requests\BaseFormRequest;

class UpdateWithdrawalStatusRequest extends BaseFormRequest
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
            'status'        => 'required|in:pending,approved,rejected,failed,cancelled,refunded,completed',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'withdrawal_id.required'    => 'يجب عليك ادخال رقم السحب',
            'withdrawal_id.exists'      => 'طلب السحب هذا غير موجود',
            'status.required'           => 'يجب عليك ادخال الحالة',
            'status.in'                 => 'يجب عليك ادخال حالة صالحة',
        ];
    }
}