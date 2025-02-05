<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['chat_id', 'user_id', 'body', 'seen', 'attachments'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'attachments' => 'array',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        // static::creating(function ($model) {
        //     $model->id = Str::uuid()->toString();
        // });
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
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
                'path' => $attachment->storeAs('attachments', uuid_create() . '.' . $attachment->getClientOriginalExtension()),
                'type' => $attachment->getClientOriginalExtension(),
                'size' => $attachment->getSize(),
                'name' => $attachment->getClientOriginalName(),
            ];
        }
        return $handledAttachments;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
