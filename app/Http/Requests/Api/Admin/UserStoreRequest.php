<?php

namespace App\Http\Requests\Api\Admin;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserStoreRequest extends BaseFormRequest
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
            'name'          => 'required|string|max:255',
            'phone'         => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8|confirmed',
            'country'       => 'required|string|max:255',
            'role'          => 'required|string|in:freelancer,client',

        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'الاسم مطلوب',
            'name.string'            => 'الاسم يجب ان يكون حروف',
            'name.max'               => 'الاسم يجب ان لا يكون اكثر من 255 حرف',
            'email.required'         => 'الايميل مطلوب',
            'email.email'            => 'الايميل غير صالح',
            'email.unique'           => 'هذا الايميل موجود بالفعل',
            'password.required'      => 'كلمة المرور مطلوبة',
            'password.min'           => 'كلمة المرور يجب ان تكون اكثر من 8 احرف',
            'country.required'       => 'البلد مطلوب',
            'country.string'         => 'البلد يجب ان يكون حروف',
            'country.max'            => 'البلد يجب ان لا يكون اكثر من 255 حرف',
            'role.required'          => 'نوع الحساب مطلوب',
            'role.in'                => 'خطأ في نوع الحساب',
        ];
    }

}
