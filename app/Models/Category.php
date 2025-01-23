<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Filterable;

class Category extends Model
{
    use HasFactory, Filterable;

    protected $fillable = ['name', 'slug', 'icon', 'description'];


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
            // ->has('skills')
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
