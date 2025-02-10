<?php

namespace App\Http\Requests\Api\Admin;

use App\Http\Requests\BaseFormRequest;

class UpdateSpecializationRequest extends BaseFormRequest
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
            'name'          => 'required|string|max:200',
            'category_id'   => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'الاسم مطلوب',
            'name.string'           => 'الاسم يجب ان يكون حروف',
            'name.max'              => 'الاسم يجب ان لا يكون اكثر من 200 حرف',
            'category_id.required'  => 'القسم مطلوب',
            'category_id.exists'    => 'القسم غير موجود',
        ];
    }
}