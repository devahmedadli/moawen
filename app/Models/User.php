<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Review;
use App\Traits\Filterable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Filterable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'specialization_id',
        'email',
        'password',
        'username',
        'balance',
        'phone',
        'birthdate',
        'gender',
        'years_of_experience',
        'bio',
        'skills',
        'country',
        'image',
        'email_verified_at',
        'status',
        'role',
        'average_rating',
        'is_2fa_enabled',
        'kyc_status',
        'account_level',
        'last_login_at',
        'last_login_ip',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'last_login_at'     => 'datetime',
            'created_at'        => 'datetime',
            'updated_at'        => 'datetime',
            'is_2fa_enabled'    => 'boolean',
            'kyc_status'        => 'string',
            'account_level'     => 'string',
            'last_login_ip'     => 'string',
            'remember_token'    => 'string',
        ];
    }


    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected function username(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => $value,
            set: function (string $value, array $attributes) {
                return $this->generateUniqueUsername(
                    $attributes['first_name'],
                    $attributes['last_name']
                );
            }
        );
    }

    protected function generateUniqueUsername(string $firstName, string $lastName): string
    {
        $baseUsername = Str::slug($firstName . ' ' . $lastName);

        // Get all existing usernames that start with the base username
        $existingUsernames = static::withTrashed()->where('username', 'LIKE', $baseUsername . '%')
            ->pluck('username')
            ->toArray();

        if (empty($existingUsernames)) {
            return $baseUsername;
        }

        // Find the highest number suffix
        $maxCounter = 1;
        foreach ($existingUsernames as $username) {
            if ($username === $baseUsername) {
                continue;
            }

            $suffix = substr($username, strlen($baseUsername));
            if (is_numeric($suffix) && (int)$suffix >= $maxCounter) {
                $maxCounter = (int)$suffix + 1;
            }
        }

        return $baseUsername . $maxCounter;
    }

    /**
     * Scope a query to only include users of a given type.
     */
    public function scopeOfType(Builder $query, string $role): void
    {
        $query->where('role', $role);
    }

    public function experienceTimeline()
    {
        return $this->hasMany(UserExperienceTimeline::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function reviews()
    {
        return $this->hasManyThrough(
            Review::class,
            Order::class,
            'seller_id', // Foreign key on orders table
            'order_id', // Foreign key on reviews table
            'id', // Local key on users table
            'id' // Local key on orders table
        );
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'freelancer_id');
    }

    public function purchases()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }


    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    protected function filterName($query, $value)
    {
        $query->where(function ($query) use ($value) {
            $query->where('first_name', 'LIKE', "%{$value}%")
                ->orWhere('last_name', 'LIKE', "%{$value}%");
        });
    }

    protected function getCustomFilterValidationRules(): array
    {
        return [
            'name'      => 'sometimes|string|max:255',
            'email'     => 'sometimes|string|email',
        ];
    }

    protected function getAllowedSortFields(): array
    {
        return ['id', 'first_name', 'last_name', 'email', 'status', 'created_at', 'updated_at', 'average_rating'];
    }

    protected function filterAccountLevel($query, $value)
    {

        $accountLevels = explode(',', $value);
        $query->whereIn('account_level', $accountLevels);
    }

    protected function filterSpecialization($query, $value)
    {
        $specializations = explode(',', $value);
        $query->whereHas('specialization', function ($query) use ($specializations) {
            $query->whereIn('id', $specializations);
        });
    }

    public function filterRandomOrder(Builder $query): Builder
    {
        return $query->orderBy(DB::raw('RAND()'));
    }

    protected function filterCategory($query, $value)
    {
        $categories = explode(',', $value);
        $query->whereHas('specialization.category', function ($query) use ($categories) {
            $query->whereIn('categories.id', $categories);
        });
    }

    protected function filterCompleteProfile($query)
    {
        $query->whereNotNull('skills')
            ->whereNotNull('bio')
            ->whereNot('bio', '');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'from_id')->orWhere('to_id', $this->id);
    }
}
