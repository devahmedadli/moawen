<?php

namespace App\Http\Requests\Api\Freelancer;

use App\Http\Requests\BaseFormRequest;

class UpdatePortfolioRequest extends BaseFormRequest
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
            'title'         => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'url'           => 'nullable|string|url',
            'images'        => 'nullable|array|min:1',
            'images.*'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_public'     => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'      => 'The user field is required.',
            'user_id.exists'        => 'The selected user is invalid.',
            'title.required'        => 'The title field is required.',
            'title.string'          => 'The title must be a string.',
            'title.max'             => 'The title may not be greater than 255 characters.',
            'description.required'  => 'The description field is required.',
            'description.string'    => 'The description must be a string.',
            'url.string'            => 'The url must be a string.',
            'url.url'               => 'The url must be a valid URL.',
            'is_public.required'    => 'The is_public field is required.',
            'is_public.boolean'     => 'The is_public field must be a boolean.',
            'images.required'       => 'The images field is required.',
            'images.array'          => 'The images must be an array.',
            'images.min'            => 'The images array must contain at least 1 item.',
            'images.*.required'     => 'The image field is required.',
            'images.*.image'        => 'The image must be an image.',
            'images.*.mimes'        => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
            'images.*.max'          => 'The image may not be greater than 2048 kilobytes.',

        ];
    }

    protected function prepareForValidation()
    {
        $this->removeNullFields();
    }
}
