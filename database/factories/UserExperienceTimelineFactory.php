<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserExperienceTimeline>
 */
class UserExperienceTimelineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users  = User::all();
        $titles = ['مهارات التطوير المنصورة', 'مهارات التطوير القاهرة', 'مهارات التطوير الجديدة'];
        $description = 'هناك حقيقة مثبتة منذ زمن طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص أو شكل توضع الفقرات في الصفحة التي يقرأها. ';
        return [
            'user_id'       => $this->faker->unique()->randomElement($users->pluck('id')->toArray()),
            'title'         => fake()->randomElement($titles),
            'description'   => $description,
            'start_date'    => fake()->date('Y-m-d'),
            'end_date'      => fake()->date('Y-m-d'),
        ];
    }
}
