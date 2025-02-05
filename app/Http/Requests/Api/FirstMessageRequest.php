<?php

namespace App\Http\Requests\Api;

use Closure;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class FirstMessageRequest extends BaseFormRequest
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
            'to_user_id'    => ['required', 'exists:users,id', function ($attribute, $value, Closure $fail) {
                if (auth()->user()->id == $value) {
                    $fail('لا يمكنك إرسال رسالة لنفسك');
                    return;
                }
            }],
            'body'          => ['required', 'string'],
            'attachments'   => ['nullable', 'array'],
            'attachments.*' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,7z'],
        ];
    }

    public function messages(): array
    {
        return [
            'to_user_id.required' => 'يجب أن يكون المستخدم موجود',
            'to_user_id.exists'   => 'المستخدم غير موجود',
            'body.required'       => 'يجب أن يكون الرسالة موجود',
            'body.string'         => 'يجب أن يكون الرسالة عبارة عن نص',
            'attachments.*.file'  => 'يجب أن يكون الملف من نوع صورة أو ملف',
            'attachments.*.mimes' => 'صيغة الملف غير مدعومة',
        ];
    }
}
