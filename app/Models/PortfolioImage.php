<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PortfolioImage extends Model
{
    use HasFactory;

    protected $fillable = ['portfolio_id', 'path', 'original_name', 'mime_type', 'size', 'order', 'is_primary'];

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

}
