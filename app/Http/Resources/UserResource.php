<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Traits\ExistsInField;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    use ExistsInField;
    /**
     * Define allowed parameters and their default values
     * This acts as a whitelist of acceptable query parameters
     */
    private array $allowedParams = [
        'all_info'          => false,
        'basic_info'        => false,
        'skills'            => false,
        'experience_timeline' => false,
        'services'          => false,
        'orders'            => false,
        'purchases'         => false,
        'reviews'           => false,
        'services_count'    => false,
        'reviews_count'     => false,
        'orders_count'      => false,
        'purchases_count'   => false,
        'basic_info'        => false,
        'created_at'        => false,
        'updated_at'        => false,
    ];

    // allowed fields
    protected array $allowedFields = [
        'specialization_id',
        'email',
        'username',
        'phone',
        'birthdate',
        'gender',
        'years_of_experience',
        'bio',
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
        'created_at',
        'updated_at',

    ];


    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get parameters using the whitelist approach
        $showAllInfo = $this->getQueryParam($request, 'all_info');

        $adminData = [];
        if (auth()->check() && auth()->user()->role === 'admin') {
            $adminData = $this->adminData($request, $showAllInfo);
        }

        return [
            ...$this->publicData($request, $showAllInfo),
            ...$adminData,
        ];
    }


    /**
     * Safely get a query parameter value
     *
     * @param Request $request
     * @param string $param
     * @return bool
     */
    private function getQueryParam(Request $request, string $param): bool
    {
        if (!array_key_exists($param, $this->allowedParams)) {
            return false;
        }
        return $request->get($param) === 'true';
    }

    /**
     * Get public data
     *
     * @return array
     */
    private function publicData(Request $request, bool $showAllInfo = false): array
    {
        return [
            'id'                => $this->id,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'specialization'    => $this->specialization?->name,
            'username'          => $this->username,
            'image'             => $this->image,
            'average_rating'    => (float) number_format((float) $this->average_rating, 1, '.', ''),
            // Basic information
            'bio'               => $this->when($showAllInfo || $this->existsInField($request, 'bio'), $this->bio),
            'country'           => $this->when($showAllInfo || $this->existsInField($request, 'country'), $this->country),
            'account_level'     => $this->when($showAllInfo || $this->existsInField($request, 'account_level'), $this->account_level),

            'skills' => $this->when($showAllInfo || $this->existsInField($request, 'skills'), json_decode($this->skills)),

            'experience_timeline' => UserExperienceTimelineResource::collection($this->whenLoaded('experienceTimeline')),

            'services' => ServiceResource::collection($this->whenLoaded('services')),

            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            // Counts (loaded only when requested)
            'services_count' => $this->whenCounted('services', fn() => $this->services_count),

            'reviews_count' => $this->whenCounted('reviews', fn() => $this->reviews_count),

            'orders_count' => $this->whenCounted('orders', function () {
                return collect([
                    'total'         => (int) $this->orders_count,
                    'pending'       => (int) $this->orders->where('status', 'pending')->count(),
                    'in_progress'   => (int) $this->orders->where('status', 'in_progress')->count(),
                    'completed'     => (int) $this->orders->where('status', 'completed')->count(),
                    'canceled'      => (int) $this->orders->where('status', 'canceled')->count(),
                ]);
            }),
            'purchases_count' => $this->whenCounted('purchases', fn() => $this->purchases_count),

        ];
    }

    /**
     * Get admin data
     *
     * @return array
     */
    private function adminData(Request $request, bool $showAllInfo = false): array
    {
        return [
            // Sensitive information (requires all_info)
            'email'             => $this->when($showAllInfo || $this->existsInField($request, 'email'), $this->email),
            'phone'             => $this->when($showAllInfo || $this->existsInField($request, 'phone'), $this->phone),
            'birthdate'         => $this->when($showAllInfo || $this->existsInField($request, 'birthdate'), $this->birthdate),
            'gender'            => $this->when($showAllInfo || $this->existsInField($request, 'gender'), $this->gender),
            'years_of_experience' => $this->when($showAllInfo || $this->existsInField($request, 'years_of_experience'), $this->years_of_experience),
            'status'            => $this->when($showAllInfo || $this->existsInField($request, 'status'), $this->status),
            'role'              => $this->when(request()->routeIs('me') || $showAllInfo || $this->existsInField($request, 'role'), $this->role),
            'is_2fa_enabled'    => $this->when($showAllInfo || $this->existsInField($request, 'is_2fa_enabled'), $this->is_2fa_enabled),
            'kyc_status'        => $this->when($showAllInfo || $this->existsInField($request, 'kyc_status'), $this->kyc_status),
            'last_login_at'     => $this->when($showAllInfo || $this->existsInField($request, 'last_login_at'), $this->last_login_at),
            'last_login_ip'     => $this->when($showAllInfo, $this->last_login_ip),
            'balance'           => $this->when($showAllInfo || $this->existsInField($request, 'balance'), $this->balance),
            // Basic information
            'created_at'        => $this->when($showAllInfo || $this->existsInField($request, 'created_at'), $this->created_at),
            'updated_at'        => $this->when($showAllInfo || $this->existsInField($request, 'updated_at'), $this->updated_at),

            'orders' => OrderResource::collection($this->whenLoaded('orders')),

            'purchases' => OrderResource::collection($this->whenLoaded('purchases')),
        ];
    }
}
