<?php

namespace App\Http\Requests\Api\Freelancer;

use App\Http\Requests\BaseFormRequest;

class StorePortfolioRequest extends BaseFormRequest
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
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'url'           => 'nullable|string|url',
            'images'        => 'required|array|min:1',
            'images.*'      => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_public'     => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'      => 'الحقل مطلوب.',
            'user_id.exists'        => 'المستخدم المختار غير صالح.',
            'title.required'        => 'الحقل مطلوب.',
            'title.string'          => 'الحقل يجب أن يكون نص.',
            'title.max'             => 'الحقل لا يمكن أن يكون أكثر من 255 حرف.',
            'description.required'  => 'الحقل مطلوب.',
            'description.string'    => 'الحقل يجب أن يكون نص.',
            'url.string'            => 'الحقل يجب أن يكون نص.',
            'url.url'               => 'الحقل يجب أن يكون رابط صالح.',
            'is_public.required'    => 'الحقل مطلوب.',
            'is_public.boolean'     => 'الحقل يجب أن يكون true أو false.',
            'images.required'       => 'الحقل مطلوب.',
            'images.array'          => 'الحقل يجب أن يكون مصفوفة.',
            'images.min'            => 'الحقل يجب أن يكون مصفوفة.',
            'images.*.required'     => 'الحقل مطلوب.',
            'images.*.image'        => 'الحقل يجب أن يكون صورة.',
            'images.*.mimes'        => 'الحقل يجب أن يكون من نوع: jpeg, png, jpg, gif, svg.',
            'images.*.max'          => 'الحقل لا يمكن أن يكون أكثر من 2048 كيلوبايت.',

        ];
    }

    protected function prepareForValidation()
    {

        $this->merge([
            'user_id'       => auth()->user()->id,
            'is_public'     => $this->is_public == 'true' ? true : false,
        ]);
    }
}
