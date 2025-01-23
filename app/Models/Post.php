<?php

namespace App\Models;

use App\Models\PostLike;
use App\Traits\Filterable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, Filterable;

    protected $fillable = ['user_id', 'title', 'content', 'image', 'read_time'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }

    protected function getAllowedSortFields(): array
    {
        return ['id', 'created_at', 'updated_at', 'likes_count', 'title'];
    }

    public function filterRandomOrder(Builder $query): Builder
    {
        return $query->orderBy(DB::raw('RAND()'));
    }

}
