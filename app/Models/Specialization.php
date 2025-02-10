<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Filterable;

class Specialization extends Model
{
    use HasFactory, Filterable;
    protected $fillable = ['name', 'category_id'];



    public function freelancers()
    {
        return $this->hasMany(User::class)->where('role', 'freelancer');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function services()
    {
        return $this->hasManyThrough(
            Service::class,
            User::class,
            'specialization_id',
            'user_id',
            'id',
            'id'
        );
    }
}
