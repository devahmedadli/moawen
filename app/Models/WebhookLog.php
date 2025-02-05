<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    protected $fillable = ['event_id', 'order_id', 'processed_at'];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
