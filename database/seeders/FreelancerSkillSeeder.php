<?php

namespace Database\Seeders;

use App\Models\FreelancerSkill;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FreelancerSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FreelancerSkill::factory()->count(10)->create();
    }
}
