<?php

namespace App\Jobs;

use App\Profile;
use App\Student;
use App\StudentData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CreateStudentAppsPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
        public $students;
        public $filename;
        public $profile;
        public $route_name;
        public $description;
        public $token;

    /**
     * Create a new job instance.
     */
    public function __construct(Profile $profile, $students, $filename, $route_name, $description, $token)
    {
        $this->profile = $profile;
        $this->students = $students;
        $this->route_name = $route_name;
        $this->filename = $filename;
        $this->description = $description;
        $this->token = $token;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pdf_data = [
            'students' => $this->students,
            'schools' => Student::participatingSchools(),
            'custom_questions' => StudentData::customQuestions(),
            'languages' => StudentData::$languages,
            'majors' => StudentData::majors(),
        ];

        $view = 'students.download';

        ProcessPdfDownload::dispatch($this->profile->user, $this->profile, $view, $this->route_name, $this->filename, "Student applications $this->description", $this->token, $pdf_data);
    }
}
