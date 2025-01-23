<?php

namespace App\Http\Requests\Api\Freelancer;

use App\Http\Requests\BaseFormRequest;


class UpdateServiceRequest extends BaseFormRequest
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
            'name'          => 'nullable|string|max:200',
            'description'   => 'nullable|string',
            'tags'          => 'nullable|array|min:1',
            'delivery_time' => 'nullable|string|max:100',
            'thumbnail'     => 'nullable|file|max:2048|image|mimes:jpeg,png,jpg,webp|dimensions:min_width=1200,min_height=800',
            'status'        => 'nullable|string|max:100',
            'price'         => 'nullable|numeric|min:0',
            'category_id'   => 'nullable|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'الاسم مطلوب',
            'name.string'         => 'الاسم يجب أن يكون سلسلة نصية',
            'name.max'            => 'الاسم يجب أن لا يكون أطول من 200 حرف',
            'description.string'  => 'الوصف يجب أن يكون سلسلة نصية',
            'tags.array'          => 'التصنيفات يجب أن تكون مصفوفة',
            'tags.min'            => 'يجب أن تكون هناك على الأقل تصنيف واحد',
            'delivery_time.string' => 'وقت التسليم يجب أن يكون سلسلة نصية',
            'delivery_time.max'    => 'وقت التسليم يجب أن لا يكون أطول من 100 حرف',
            'thumbnail.file'      => 'الصورة يجب أن تكون ملف',
            'thumbnail.max'       => 'الصورة يجب أن لا تكون أطول من 2048 كيلوبايت',
            'thumbnail.image'     => 'الصورة يجب أن تكون صورة',
            'thumbnail.mimes'     => 'الصورة يجب أن تكون من نوع jpeg, png, jpg, webp',
            'thumbnail.dimensions' => 'الصورة يجب أن تكون عرضها أكبر من 1200 وارتفاعها أكبر من 800',
            'status.string'        => 'الحالة يجب أن تكون سلسلة نصية',
            'status.max'           => 'الحالة يجب أن لا يكون أطول من 100 حرف',
            'price.numeric'        => 'السعر يجب أن يكون رقم',
            'price.min'            => 'السعر يجب أن لا يكون أقل من 0',
            'category_id.exists'   => 'التصنيف غير موجود',

        ];
    }

    protected function prepareForValidation()
    {
        // dd($this->all());
        $this->removeNullFields();
    }
}
