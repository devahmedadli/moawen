<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class Update2FARequest extends BaseFormRequest
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
        if (!$this->enabled) {
            return [
                'enabled' => ['required', 'boolean'],
            ];
        }

        return [
            'email'     => ['nullable', 'email', 'unique:users,email,' . Auth::user()->id, 'email:rfc,dns', Rule::requiredIf(fn() => $this->enabled && !$this->phone)],
            'phone'     => ['nullable', 'string', 'unique:users,phone,' . Auth::user()->id, Rule::requiredIf(fn() => $this->enabled && !$this->email)],
            'enabled'   => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'يرجى اضافة البريد الإلكتروني أو الهاتف',
            'email.unique'      => 'البريد الإلكتروني موجود بالفعل',
            'email.email'       => 'البريد الإلكتروني غير صالح',
            'phone.required'    => 'يرجى اضافة البريد الإلكتروني أو الهاتف',
            'phone.unique'      => 'الهاتف موجود بالفعل',
            'phone.string'      => 'الهاتف غير صالح',
            'enabled.required'  => 'الحالة مطلوبة',
            'enabled.boolean'   => 'يرجى تحديد الحالة',

        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'enabled' => 'التحقق من خطوتين',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'enabled' => $this->enabled === 'true',
        ]);
    }
}
