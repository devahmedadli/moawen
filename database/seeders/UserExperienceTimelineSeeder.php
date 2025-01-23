<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserExperienceTimeline;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserExperienceTimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserExperienceTimeline::factory()->count(20)->create();
    }
}
