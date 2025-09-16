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
        public $download_route_name;
        public $description;
        public $token;

    /**
     * Create a new job instance.
     */
    public function __construct(Profile $profile, $students, $filename, $download_route_name, $description, $token)
    {
        $this->profile = $profile;
        $this->students = $students;
        $this->download_route_name = $download_route_name;
        $this->filename = $filename;
        $this->token = $token;
        $this->description = $description;
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

        ProcessPdfJob::dispatch($this->profile->user, $view, $this->download_route_name, $this->filename, "Student applications $this->description", $this->token, $pdf_data);
    }
}
