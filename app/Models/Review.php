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

    // public function service()
    // {
    //     return $this->belongsTo(Service::class, 'order_id')
    //         ->join('orders', 'orders.id', '=', 'reviews.order_id')
    //         ->join('services', 'services.id', '=', 'orders.service_id')
    //         ->withTrashed();
    // }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
