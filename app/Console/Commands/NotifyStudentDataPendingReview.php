<?php

namespace App\Console\Commands;

use App\Helpers\Semester;
use App\Mail\StudentDataReceived;
use App\Profile;
use App\StudentData;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

use function PHPUnit\Framework\isNull;

class NotifyStudentDataPendingReview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'studentdata:notify {season?} {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loop through the faculty members that have applications received pending review';

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
    public function send_message($email, $name, $count, $semester, $faculty, $delegate = false):void {
        
        $message = new StudentDataReceived($name, $count, $semester, $faculty, $delegate);
                
        Mail::to($email)->send($message);
        $this->line("Message sent to: {$name}");
    }

}
