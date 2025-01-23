<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserSkill>
 */
class UserSkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::where('role', 'freelancer')->inRandomOrder()->get();
        $skills = [
            'HTML',
            'CSS',
            'JavaScript',
            'PHP',
            'MySQL',
            'Python',
            'Java',
            'C#',
            'C++',
            'C',
            'Laravel',
            'Vue.js',
            'React.js',
        ];

        return [
            'user_id' => $users->random()->id,
            'skill'   => fake()->randomElement($skills),
        ];
    }
}
