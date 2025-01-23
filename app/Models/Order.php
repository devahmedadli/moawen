<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use Filterable, HasFactory;

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'service_id',
        'deadline',
        'price',
        'rating_given',
        'status',
        'is_paid',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'buyer_id')->withTrashed();
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'seller_id')->withTrashed();
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function filterStatus($query, $value)
    {
        $statuses = explode(',', $value);
        $query->whereIn('status', $statuses);
    }

    protected function getAllowedSortFields(): array
    {
        return ['created_at', 'total_amount'];
    }

}
