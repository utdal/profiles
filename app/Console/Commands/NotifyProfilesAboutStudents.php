<?php

namespace App\Console\Commands;

use App\Helpers\Semester;
use App\Mail\ReviewStudents;
use App\Profile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyProfilesAboutStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profiles:notify-about-students
                            {season? : The student application season, e.g. Fall, Spring, Summer. Default: the current season.}
                            {year? : The student application year, e.g. 2022. Default: the current year.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications to profile users about new student applications for the given semester';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $season = $this->argument('season');
        $year = $this->argument('year');

        $semester = ($season && $year) ? Semester::formatName($season, $year) : Semester::current();
        $faculty_list = Profile::StudentsPendingReviewWithSemester($semester)
                                ->EagerStudentsPendingReviewWithSemester($semester)
                                ->with('user:id,email,display_name')->get();

        foreach ($faculty_list as $faculty) { 
            $count = $faculty->students->count();

            $this->send_message($faculty->user->email, $faculty->full_name, $count, $semester, $faculty);
       
            $faculty->user->currentReminderDelegates->each(function($delegate) use ($count, $semester, $faculty) 
            {
                $this->send_message($delegate->email, $delegate->display_name, $count, $semester, $faculty, true);
            });
        }

        return Command::SUCCESS;
    }

    /**
     * Send email to faculty member or delegate(s) and output a message to the console
     */
    public function send_message($email, $name, $count, $semester, $faculty, $delegate = false):void
    {
        Mail::to($email)
            ->send(new ReviewStudents($name, $count, $semester, $faculty, $delegate));

        $this->line("Message sent to: {$name}");
    }

}
