<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateServiceRequest extends FormRequest
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
        dd($this->all());
        return [
            'name'          => 'required|string|max:200',
            'description'   => 'required|string',
            'response_time' => 'required|string|max:100',
            'tags'          => 'nullable|string|max:255',
            'price'         => 'required|numeric|min:0',
            'delivery_time' => 'required|string|max:100',
            'thumbnail_url' => 'nullable|string|max:255',
            'status'        => 'required|in:active,inactive',
            'category_id'   => 'required|exists:categories,id',
            'user_id'       => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'الاسم مطلوب',
            'name.string'           => 'الاسم يجب ان يكون حروف',
            'name.max'              => 'الاسم يجب ان لا يكون اكثر من 200 حرف',
            'description.required'  => 'الوصف مطلوب',
            'response_time.required' => 'وقت الاستجابة مطلوب',
            'response_time.max'     => 'وقت الاستجابة يجب ان لا يكون اكثر من 100 حرف',
            'tags.string'           => 'التطابقات يجب ان يكون حروف',
            'tags.max'              => 'التطابقات يجب ان لا يكون اكثر من 255 حرف',
            'price.required'        => 'السعر مطلوب',
            'price.numeric'         => 'السعر يجب ان يكون رقم',
            'price.min'             => 'السعر يجب ان لا يكون اقل من 0',
            'delivery_time.required' => 'وقت التسليم مطلوب',
            'delivery_time.max'     => 'وقت التسليم يجب ان لا يكون اكثر من 100 حرف',
            'thumbnail_url.string'  => 'الصورة يجب ان يكون حروف',
            'thumbnail_url.max'     => 'الصورة يجب ان لا يكون اكثر من 255 حرف',
            'status.required'       => 'الحالة مطلوبة',
            'status.in'             => 'الحالة يجب ان تكون مفعل او غير مفعل',
            'category_id.required'  => 'الفئة مطلوبة',
            'category_id.exists'    => 'الفئة غير موجودة',
            'user_id.required'      => 'المستخدم مطلوب',
            'user_id.exists'        => 'المستخدم غير موجود',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            apiResponse($validator->errors(), 'خطأ في التحقق من البيانات', 422)
        );
    }
}
