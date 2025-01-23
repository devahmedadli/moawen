<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSkill extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'skill'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
