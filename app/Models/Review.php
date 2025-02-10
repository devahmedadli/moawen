<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Filterable;

class Review extends Model
{
    use HasFactory, Filterable;
    protected $fillable = [
        'user_id',
        'order_id',
        'rating',
        'comment',
    ];


    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function service()
    {
        return $this->hasOneThrough(Service::class, Order::class, 'id', 'id', 'id', 'service_id');
    }
}
