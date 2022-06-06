<?php

namespace Database\Seeders;

use App\Student;
use App\StudentData;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a random User, Student, and StudentData
        Student::factory()
            ->submitted()
            ->has(StudentData::factory(), 'research_profile')
            ->create();
    }
}
