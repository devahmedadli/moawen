<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use Filterable;

    protected $fillable = ['user_id', 'amount', 'method', 'status', 'reason'];

    public function user ()
    {
        return $this->belongsTo(User::class);
    }
}
