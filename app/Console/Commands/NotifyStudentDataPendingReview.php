<?php

namespace App\Console\Commands;

use App\Helpers\Semester;
use App\Mail\StudentDataReceived;
use App\Profile;
use App\StudentData;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

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

        $semester = ($season & $year) ? Semester::formatName($season, $year) : Semester::current();
        $apps_count = StudentData::applications_count_by_faculty($semester);

        foreach ($apps_count as $faculty_apps) { 
                
                $count = $faculty_apps['count'];           
                $user = $faculty_apps['user'];
                $message = new StudentDataReceived($user, $count, $semester);
                
                Mail::to($user['email'])->send($message);
                $this->line("Message sent to: {$user['full_name']}");
            
        }

        return Command::SUCCESS;
    }
}
