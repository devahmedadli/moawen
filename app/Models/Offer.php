<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'service_id',
        'freelancer_id',
        'client_id',
        'price',
        'deadline',
        'status'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
