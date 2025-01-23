<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FreelancerSkill>
 */
class FreelancerSkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
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
            'user_id' => User::where('role', 'freelancer')->inRandomOrder()->first()->id,
            'skills'        => json_encode($skills),

        ];
    }
}
