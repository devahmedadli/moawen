<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            AdminSeeder::class,
            CategorySeeder::class,
            SpecializationSeeder::class,
            UserSeeder::class,
            ServiceSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
            PostSeeder::class,
            PostLikeSeeder::class,
            UserSkillSeeder::class,
            UserExperienceTimelineSeeder::class,
        ]);
    }
}
