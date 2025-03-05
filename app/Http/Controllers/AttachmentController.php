<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttachmentController extends Controller
{
    /**
     * Download the attachment
     *
     * @param Attachment $attachment
     * @return \Illuminate\Http\Response
     */
    public function download(Attachment $attachment)
    {
        if (!auth()->check()) {
            return $this->error('يجب عليك تسجيل الدخول لتحميل الملفات', 401);
        }

        if (!in_array(auth()->user()->id, [$attachment->attachmentable->chat->user_1_id, $attachment->attachmentable->chat->user_2_id])) {
            return $this->error('ليس لديك الصلاحية لتحميل هذا الملف', 403);
        }

        if (!Storage::exists($attachment->path)) {
            abort(404);
        }

        return Storage::download(
            $attachment->path,
            $attachment->name
        );
    }
}
