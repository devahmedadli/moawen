<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\ActiveServiceScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory, Filterable, SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
        'category_id',
        'description',
        'service_level',
        'response_time',
        'tags',
        'price',
        'delivery_time',
        'thumbnail',
        'status',
        'average_rating',
        'views'

    ];

    protected $casts = [
        'tags'          => 'array',
        'thumbnail'     => 'string',
        'status'        => 'string',
        'average_rating' => 'float',
        'views'         => 'integer',

    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new ActiveServiceScope);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function reviews()
    {
        return $this->hasManyThrough(
            Review::class,
            Order::class,
            'service_id',
            'order_id'
        );
    }

    public function specialization()
    {
        return $this->hasOneThrough(Specialization::class, User::class, 'id', 'id', 'user_id', 'specialization_id');
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    protected function getAllowedSortFields(): array
    {
        return ['id', 'created_at', 'updated_at', 'average_rating', 'views', 'category_id', 'price'];
    }

    protected function getCustomFilterValidationRules(): array
    {
        return [
            'name'          => 'sometimes|string',
            'category_id'   => 'sometimes|exists:categories,id',
            'service_level' => 'sometimes|string|in:beginner,intermediate,advanced',
            'price_min'     => 'sometimes|numeric|min:0',
            'price_max'     => 'sometimes|numeric|min:0',
            'created_at_range' => 'sometimes|string|in:last_hour,last_24_hours,last_7_days,last_14_days',
            'status'        => 'sometimes|string|in:active,inactive',
        ];
    }

    protected function filterPriceMin($query, $value)
    {
        $query->where('price', '>=', $value);
    }

    protected function filterPriceMax($query, $value)
    {
        $query->where('price', '<=', $value);
    }

    protected function filterCreatedAtRange($query, $value)
    {
        $ranges = [
            'last_hour'     => now()->subHour(),
            'last_24_hours' => now()->subDay(),
            'last_7_days'   => now()->subDays(7),
            'last_14_days'  => now()->subDays(14),
        ];

        if (isset($ranges[$value])) {
            $query->where('created_at', '>=', $ranges[$value]);
        }
    }
    protected function filterServiceLevel($query, $value): void
    {
        // Validate against allowed values defined in validation rules
        $levels = explode(',', $value);
        if (!empty($levels)) {
            $query->whereIn('service_level', $levels);
        }
    }

    protected function filterCategory($query, $value): void
    {
        // Ensure all values are numeric to prevent SQL injection
        $categories = array_filter(explode(',', $value), 'is_numeric');
        if (!empty($categories)) {
            $query->whereIn('category_id', $categories);
        }
    }

    protected function filterName($query, $value): void
    {
        // Escape special characters to prevent SQL injection
        $sanitizedValue = str_replace(['%', '_'], ['\%', '\_'], trim($value));
        $query->where('name', 'like', '%' . $sanitizedValue . '%');
    }

    protected function filterStatus($query, $value): void
    {
        // Validate against allowed values
        if (in_array($value, ['active', 'inactive'])) {
            $query->where('status', $value);
        }
    }

    public function filterRandomOrder(Builder $query): Builder
    {
        return $query->orderBy(DB::raw('RAND()'));
    }
}
