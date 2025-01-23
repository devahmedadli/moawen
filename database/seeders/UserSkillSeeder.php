<?php

namespace Database\Seeders;

use App\Models\UserSkill;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserSkill::factory()->count(100)->create();
    }
}
