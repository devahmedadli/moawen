<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, Filterable;

    protected $fillable = ['name', 'slug', 'icon', 'description'];

    public function slug(): Attribute
    {
        return new Attribute(
            set: fn($value) => Str::slug($value),
        );
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function specializations()
    {
        return $this->hasMany(Specialization::class);
    }

    public function freelancers()
    {
        return $this->hasManyThrough(User::class, Specialization::class)
            ->where('role', 'freelancer')
            ->whereNotNull('bio')
            ->where('bio', '!=', '')
            ->whereNull('users.deleted_at');
    }

    protected function getAllowedSortFields(): array
    {
        return ['id', 'created_at', 'updated_at', 'name'];
    }

    protected function getCustomFilterValidationRules(): array
    {
        return [
            'name' => 'sometimes|string',
        ];
    }

    // protected function filterCompleteProfile($query)
    // {
    //     return $query->whereHas('freelancers', function ($query) {
    //         $query->has('skills')
    //             ->whereNotNull('bio')
    //             ->where('bio', '!=', '');
    //     });
    // }
}
