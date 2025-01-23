<?php

namespace App\Http\Requests\Api\Freelancer;

use App\Http\Requests\BaseFormRequest;

class StoreServiceRequest extends BaseFormRequest
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
            'user_id'       => 'required|exists:users,id',
            'name'          => 'required|string|max:200',
            'description'   => 'required|string',
            'tags'          => 'required|array|min:1',
            'delivery_time' => 'required|string|max:100',
            'thumbnail'     => 'required|file|max:2048|image|mimes:jpeg,png,jpg,webp|dimensions:min_width=1200,min_height=800',
            'price'         => 'required|numeric|min:0',
            'category_id'   => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'          => 'حدث خطأ ما',
            'name.required'             => 'يجب أن يكون للخدمة إسم',
            'name.max'                  => 'يجب أن لا يكون طول إسم الخدمة أكثر من 200 حرف',
            'description.required'      => 'أضف وصفا للخدمة',
            'tags.required'             => 'يجب أن تحتوي الخدمة على علامات',
            'tags.min'                  => 'يجب أن تحتوي الخدمة على علامات',
            'tags.array'                => 'خطأ في البيانات',
            'delivery_time.required'    => 'أضف زمن تسليم الخدمة',
            'delivery_time.max'         => 'يجب أن لا يكون طول زمن تسليم الخدمة أكثر من 100 حرف',
            'thumbnail.required'        => 'أضف صورة للخدمة',
            'thumbnail.file'            => 'يجب أن يكون الصورة عبارة عن ملف',
            'thumbnail.max'             => 'يجب أن لا يكون حجم الصورة أكثر من 2048 كيلو',
            'thumbnail.image'           => 'صورة غير صالحة',
            'thumbnail.mimes'           => 'يجب اختيار صورة بصيغة jpeg, png, jpg, webp',
            'thumbnail.dimensions'      => 'يجب أن لا تقل ابعاد الصورة عن 1200 x800',
            'category_id.required'      => 'يجب تحديد تصنيف الخدمة',
            'category_id.exists'        => 'التصنيف غير موجود',
            'price.required'            => 'أضف سعر الخدمة',
            'price.numeric'             => 'يجب أن يكون سعر الخدمة عدداً',
            'price.min'                 => 'يجب أن لا يكون سعر الخدمة أقل من 0',

        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'user_id'       => auth()->user()->id,
        ]);
    }
}
