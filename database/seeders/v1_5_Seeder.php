<?php

namespace Database\Seeders;

use App\Profile;
use App\StudentData;
use Illuminate\Database\Seeder;

class v1_5_Seeder extends Seeder
{
    /**
     * Updates data for release v1.5
     *
     * @return void
     */
    public function run()
    {
        $this->convertStudentFacultyInterests();
    }

    /**
     * Profiles v1.5 changed the storage of Student application
     * selected faculty from an array of names on StudentData to
     * a pivot table.
     * 
     * This seeder is to migrate previously-submitted data
     * to that new schema.
     *
     * @return void
     */
    public function convertStudentFacultyInterests()
    {
        $studentdata_with_faculty = StudentData::with('student')->whereNotNull('data->faculty')->get();
        $missing_profiles = collect();

        foreach ($studentdata_with_faculty as $studentdata) {
            $profiles = collect();

            // find the appropriate profiles based on the previously-saved names
            foreach ($studentdata->faculty as $faculty_name) {
                $found_profile = Profile::firstWhere('full_name', '=', $faculty_name);
                if ($found_profile !== null) {
                    $profiles->push($found_profile);
                } else {
                    $missing_profiles->push([
                        'student_id' => $studentdata->student->id,
                        'faculty_name' => $faculty_name,
                    ]);
                }
            }

            // create the v1.5-style association
            $changes = $studentdata->student->faculty()->sync($profiles->filter()->pluck('id')->all());
            $created = count($changes['attached'] ?? []);

            $this->command->info("Created $created faculty Profile association(s) for Student {$studentdata->student->id} ({$studentdata->student->full_name})");

            if ($created !== count($studentdata->faculty)) {
                $this->command->warn("...but should have created " . count($studentdata->faculty));
            }

            // optional: delete the pre-v1.5-style faculty data from the student application?
            // Hmmmm. We'll leave it for now.
        }

        // Report any Profiles we were unable to find
        foreach ($missing_profiles->sortBy('faculty_name') as $missing_profile) {
            $this->command->warn("Couldn't find Profile with name \"{$missing_profile['faculty_name']}\" to associate with Student {$missing_profile['student_id']}");
        }
    }
}
