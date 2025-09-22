<?php

namespace App\Jobs;

use App\Student;
use App\StudentData;
use App\User;
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
        public $user;
        public $download_route_name;
        public $description;
        public $token;
        public $model;
        public $ability;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, $students, $filename, $download_route_name, $description, $token, $model, $ability)
    {
        $this->user = $user;
        $this->students = $students;
        $this->download_route_name = $download_route_name;
        $this->filename = $filename;
        $this->token = $token;
        $this->description = $description;
        $this->model = $model;
        $this->ability = $ability;
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
            'user' => $this->user,
        ];

        $view = 'students.download';

        ProcessPdfJob::dispatch($this->user, $view, $this->download_route_name, $this->filename, "Student applications $this->description", $this->token, $pdf_data, $this->model, $this->ability);
    }
}
