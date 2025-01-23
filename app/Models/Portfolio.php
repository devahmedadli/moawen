<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Portfolio extends Model
{
    use HasFactory, Filterable;

    protected $fillable = ['user_id', 'title', 'description', 'url', 'is_public'];

    public function images(): HasMany
    {
        return $this->hasMany(PortfolioImage::class);
    }
}
