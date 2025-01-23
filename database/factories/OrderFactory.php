<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $services   = Service::all();
        $buyers     = User::ofType('client')->get();
        $sellers    = User::ofType('freelancer')->get();
        return [
            'buyer_id'      => $buyers->random()->id,
            'seller_id'     => $sellers->random()->id,
            'service_id'    => $services->random()->id,
            'delivery_date' => now()->addDays(rand(1, 10)),
            'total_amount'  => fake()->numberBetween(100, 1000),
            'rating_given'  => fake()->randomElement([true, false]),
            'status'        => fake()->randomElement(['pending', 'in_progress', 'completed', 'canceled', 'rejected', 'accepted']),
            'created_at'    => now(),
        ];
    }
}
