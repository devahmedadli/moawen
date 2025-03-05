<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['chat_id', 'user_id', 'body', 'seen'];
    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    // protected $with = ['attachments'];
    
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('attachments', function ($query) {
            $query->with('attachments');
        });
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
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
