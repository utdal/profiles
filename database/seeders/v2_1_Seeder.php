<?php

namespace Database\Seeders;

use App\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class v2_1_Seeder extends Seeder
{
    /**
     * Updates student records by chunks
     */
    public function run(): void 
    {
        Student::query()
            ->whereNull('slug')
            ->orderBy('id')
            ->chunkById(200, function ($students) {
                $this->command->withProgressBar($students, fn($student) => $this->updateStudent($student));
                $this->command->newLine();
            });
    }

    /**
     * Replaces a student slug with a unique ULID
     */
    public function updateStudent(Student $student): void
    {
        $existing_student = DB::table('students')->where('id', $student->id) ?? null;

        if ($existing_student) {
            $existing_student->update(['slug' => Str::ulid()]);
        }
    }
}
