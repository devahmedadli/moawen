<?php

namespace App\Http\Requests\Api;

use Closure;
use App\Http\Requests\BaseFormRequest;

class StoreMessageRequest extends BaseFormRequest
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
            'chat_id'           => ['required', 'exists:chats,id', function ($attribute, $value, Closure $fail) {
                $chat = \App\Models\Chat::find($value);
                if (!$chat) {
                    $fail('المحادثة غير موجودة');
                    return;
                }
                if (!in_array(auth()->user()->id, [$chat->user_1_id, $chat->user_2_id])) {
                    $fail('ليس لديك الصلاحية لإرسال الرسائل في هذه المحادثة');
                }
            }],
            // 'user_id'           => 
            'body'              => ['required', 'string'],
            'attachments'       => ['nullable', 'array'],
            'attachments.*'     => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,7z'],
        ];
    }

    public function messages(): array
    {
        return [
            'chat_id.required'      => 'حدث خطأ ما',
            'chat_id.exists'        => 'المحادثة غير موجودة',
            'chat_id.not_allowed'   => 'ليس لديك الصلاحية لإرسال الرسائل في هذه المحادثة',
            'body.required'         => 'يجب أن تقوم بإرسال الرسالة',
            'body.string'           => 'يجب أن تكون الرسالة عبارة عن نص',
            'attachments.*.file'    => 'يجب أن يكون الملف من نوع صورة أو ملف',
            'attachments.*.mimes'   => 'صيغة الملف غير مدعومة',
        ];
    }

    // public function prepareForValidation()
    // {
    //     $this->replace([
    //         'chat_id'   => $this->chat_id ?? \App\Models\Chat::create([
    //             'from_id' => auth()->user()->id,
    //             'to_id' => $this->to_id,
    //         ])->id,
    //     ]);
    // }
}
