<?php

namespace App\Http\Requests\Api\Admin;

use App\Http\Requests\BaseFormRequest;

class UpdateServiceStatusRequest extends BaseFormRequest
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
        // dd($this->all());
        return [
            'status'        => ['required', 'string', 'in:pending,revision,approved,rejected,published,unpublished'],
            'admin_notes'   => ['nullable', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'status.required'   => 'يجب عليك تحديد حالة الخدمة',
            'status.string'     => 'حالة الخدمة يجب أن تكون نصية',
            'status.in'         => 'حالة الخدمة غير صالحة',
            'admin_notes.string' => 'ملاحظات الادمن يجب أن تكون نصية'

        ];
    }
}
