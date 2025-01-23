<?php

namespace App\Http\Requests\Api\Admin;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ApiResponse;

class StoreFreelancerRequest extends BaseFormRequest
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
            'first_name'    => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s-]+$/u'],
            'last_name'     => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s-]+$/u'],
            'email'         => ['required', 'email:rfc,dns', 'unique:users,email'],
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
            'country'       => ['required', 'string', 'max:255'],
            'phone'         => ['required', 'string', 'max:255', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'terms'         => ['required', 'accepted'],
            'specialization_id' => ['required', 'exists:specializations,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'   => 'الاسم الاول مطلوب',
            'first_name.string'     => 'الاسم الاول يجب أن يكون نصاً',
            'first_name.max'        => 'الاسم الاول يجب ألا يتجاوز 255 حرفاً',
            'first_name.regex'      => 'الاسم الاول يجب أن يحتوي على أحرف فقط',

            'last_name.required'    => 'الاسم الثاني مطلوب',
            'last_name.string'      => 'الاسم الثاني يجب أن يكون نصاً',
            'last_name.max'         => 'الاسم الثاني يجب ألا يتجاوز 255 حرفاً',
            'last_name.regex'       => 'الاسم الثاني يجب أن يحتوي على أحرف فقط',

            'email.required'        => 'البريد الإلكتروني مطلوب',
            'email.email'           => 'البريد الإلكتروني غير صالح',
            'email.unique'          => 'البريد الإلكتروني موجود بالفعل',

            'password.required'     => 'الرقم السري مطلوب',
            'password.confirmed'    => 'الرقم السري غير متطابق',
            'password.max'          => 'الرقم السري يجب ألا يتجاوز 100 حرف',
            'password.min'          => 'الرقم السري يجب أن يكون أطول من 8 أحرف',
            'password.mixed'        => 'الرقم السري يجب أن يحتوي على أحرف كبيرة وصغيرة',
            'password.numbers'      => 'الرقم السري يجب أن يحتوي على أرقام',
            'password.symbols'      => 'الرقم السري يجب أن يحتوي على رموز خاصة',
            'password.uncompromised' => 'الرقم السري غير آمن، الرجاء اختيار رقم سري آخر',

            'country.required'      => 'الجنسية مطلوبة',
            'country.string'        => 'الجنسية يجب أن تكون نصاً',
            'country.max'           => 'الجنسية يجب ألا تتجاوز 255 حرفاً',

            'phone.required'        => 'الهاتف مطلوب',
            'phone.string'          => 'الهاتف يجب أن يكون نصاً',
            'phone.max'             => 'الهاتف يجب ألا يتجاوز 255 حرفاً',
            'phone.regex'           => 'صيغة رقم الهاتف غير صحيحة',

            'terms.required'        => 'يجب الموافقة على الشروط والأحكام',
            'terms.accepted'        => 'يجب الموافقة على الشروط والأحكام',

            'specialization_id.required' => 'التخصص مطلوب',
            'specialization_id.exists'  => 'التخصص غير موجود',
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name'    => 'الاسم الاول',
            'last_name'     => 'الاسم الثاني',
            'email'         => 'البريد الإلكتروني',
            'password'      => 'الرقم السري',
            'nationality'   => 'الجنسية',
            'phone'         => 'الهاتف',
            'terms'         => 'الشروط والأحكام',
        ];
    }
}
