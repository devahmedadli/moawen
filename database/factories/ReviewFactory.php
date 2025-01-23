<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $users    = User::ofType('client')->get();
        $orders   = Order::where('rating_given', 'yes')->get();
        $comments = [
            'عمل رائع! كان المطور محترفًا جدًا وسريع الاستجابة',
            'خدمة ممتازة، سأتعامل معه مرة أخرى بالتأكيد',
            'تجربة إيجابية للغاية، تم تسليم المشروع قبل الموعد المحدد',
            'مطور موهوب جداً ويتواصل بشكل جيد، أنصح بالتعامل معه',
            'جودة العمل فاقت توقعاتي، شكراً جزيلاً على المجهود الرائع'
        ];
        return [
            'user_id'       => $users->random()->id,
            'order_id'      => $orders->random()->id,
            'rating'        => $this->faker->numberBetween(1, 5),
            'comment'       => fake()->randomElement($comments),
        ];
    }
}
