<?php

namespace App\Http\Requests\Api\Admin;


use App\Http\Requests\BaseFormRequest;

class StoreCategoryRequest extends BaseFormRequest
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
            'slug'          => 'required|string|max:200|unique:categories,slug',
            'description'   => 'nullable|string|max:500',
            'icon'          => 'nullable|string|max:500',

        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'الاسم مطلوب',
            'slug.required'     => 'الاسم المختصر مطلوب',
            'slug.unique'       => 'الاسم المختصر موجود بالفعل',
            'name.max'          => 'الاسم يجب ان لا يكون اكثر من 200 حرف',
            'slug.max'          => 'الاسم المختصر يجب ان لا يكون اكثر من 200 حرف',
            'description.max'   => 'الوصف يجب ان لا يكون اكثر من 500 حرف',
            'icon.max'          => 'الرمز يجب ان لا يكون اكثر من 500 حرف',
            'name.string'       => 'الاسم يجب ان يكون حروف',
            'slug.string'       => 'الاسم المختصر يجب ان يكون حروف',
            'description.string' => 'الوصف يجب ان يكون حروف',
            'icon.string'       => 'الرمز يجب ان يكون حروف',

        ];
    }

}
