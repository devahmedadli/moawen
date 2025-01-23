<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseFormRequest;

class UpdateProfileInfo extends BaseFormRequest
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
            'first_name'        => ['nullable', 'string', 'max:255'],
            'last_name'         => ['nullable', 'string', 'max:255'],
            'email'             => ['nullable', 'string', 'email:dns,rfc', 'max:255', 'unique:users,email,' . auth()->id()],
            'phone'             => ['nullable', 'string', 'max:255', 'unique:users,phone,' . auth()->id()],
            'bio'               => ['nullable', 'string', 'max:2000'],
            'country'           => ['nullable', 'string', 'max:255'],
            'birthdate'         => ['nullable', 'date'],
            'specialization_id' => ['nullable', 'exists:specializations,id'],
            'years_of_experience'  => ['nullable', 'integer', 'min:0', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'   => 'الاسم الأول مطلوب',
            'first_name.string'     => 'الاسم الأول يجب أن يكون حروف',
            'first_name.max'        => 'الاسم الأول يجب أن يكون أقل من 255 حرف',
            'last_name.required'    => 'الاسم الأخير مطلوب',
            'last_name.string'      => 'الاسم الأخير يجب أن يكون حروف',
            'last_name.max'         => 'الاسم الأخير يجب أن يكون أقل من 255 حرف',
            'email.required'        => 'البريد الإلكتروني مطلوب',
            'email.email'           => 'البريد الإلكتروني غير صالح',
            'email.max'             => 'البريد الإلكتروني يجب أن يكون أقل من 255 حرف',
            'email.unique'          => 'البريد الإلكتروني موجود بالفعل',
            'phone.required'        => 'الرقم الهاتف مطلوب',
            'phone.max'             => 'الرقم الهاتف يجب أن يكون أقل من 255 حرف',
            'phone.unique'          => 'الرقم الهاتف موجود بالفعل',
            'bio.max'               => 'السيرة الذاتية يجب أن يكون أقل من 2000 حرف',
            'specialization_id.required' => 'التخصص مطلوب',
            'specialization_id.exists' => 'التخصص غير موجود',
            'years_of_experience.min' => 'يجب أن يكون سنوات الخبرة أكبر من 0',
            'years_of_experience.max' => 'يجب أن يكون سنوات الخبرة أقل من 50',
            'country.required'        => 'البلد مطلوب',
            'birthdate.date'          => 'تاريخ الميلاد غير صالح',

        ];
    }

    /**
     * Remove empty values from the request
     * @return void
     */
    public function prepareForValidation()
    {
        $this->removeNullFields();
    }

    
}
