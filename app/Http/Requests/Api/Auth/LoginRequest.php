<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends BaseFormRequest
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
            'email'     => 'required|email|exists:users,email',
            'password'  => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists'          => 'البريد الإلكتروني غير موجود',
            'email.required'        => 'البريد الإلكتروني مطلوب',
            'email.email'           => 'البريد الإلكتروني غير صالح',
            'password.required'     => 'الرقم السري مطلوب',
        ];
    }
}
