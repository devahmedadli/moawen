<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends BaseFormRequest
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
            'old_password'  => ['required', 'string', 'min:8', 'current_password'],
            'password'      => [
                'required',
                'confirmed',
                'max:100',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'old_password.required' => 'كلمة المرور القديمة مطلوبة',
            'old_password.current_password' => 'كلمة المرور القديمة غير صحيحة',
            'password.required'     => 'الرقم السري مطلوب',
            'password.confirmed'    => 'الرقم السري غير متطابق',
            'password.max'          => 'الرقم السري يجب ألا يتجاوز 100 حرف',
            'password.min'          => 'الرقم السري يجب أن يكون أطول من 8 أحرف',
            'password.mixed'        => 'الرقم السري يجب أن يحتوي على أحرف كبيرة وصغيرة',
            'password.numbers'      => 'الرقم السري يجب أن يحتوي على أرقام',
            'password.symbols'      => 'الرقم السري يجب أن يحتوي على رموز خاصة',
            'password.uncompromised' => 'الرقم السري غير آمن، الرجاء اختيار رقم سري آخر',

        ];
    }

    
}
