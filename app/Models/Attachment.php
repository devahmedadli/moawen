<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'attachmentable_id',
        'attachmentable_type',
        'name',
        'path',
        'type',
        'size',
    ];

    public function attachmentable()
    {
        return $this->morphTo();
    }


    /**
     * Handle attachments 
     *
     * @param array $attachments
     * @return array
     */
    public static function handleAttachments($attachments)
    {
        if (empty($attachments)) {
            return [];
        }
        $handledAttachments = [];
        foreach ($attachments as $attachment) {
            $handledAttachments[] = [
                'name' => $attachment->getClientOriginalName(),
                'path' => $attachment->storeAs('attachments', uuid_create() . '.' . $attachment->getClientOriginalExtension()),
                'type' => $attachment->getClientOriginalExtension(),
                'size' => $attachment->getSize(),
            ];
        }
        return $handledAttachments;
    }

    /**
     * Get the download URL for the attachment
     *
     * @return string
     */
    public function getDownloadUrlAttribute()
    {
        return route('attachments.download', $this->id);
    }
}
