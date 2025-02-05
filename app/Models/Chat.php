<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Filterable;

class Chat extends Model
{
    use HasFactory, Filterable;

    protected $fillable = ['id', 'order_id', 'user_1_id', 'user_2_id', 'last_message_at'];


    protected static function boot () 
    {
        parent::boot();
        // static::creating(function ($model) {
        //     $model->id = Str::uuid()->toString();
        // });
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user1()
    {
        return $this->belongsTo(User::class, 'user_1_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user_2_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'desc');
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }
}
