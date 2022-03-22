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
        $student_data_count = StudentData::student_data_count_by_faculty($semester);
        $users = User::whereIn('id', array_keys($student_data_count))->get();

        foreach ($users as $user) {            
            if ($user) {
                $count = $student_data_count[$user->id];
                $message = new StudentDataReceived($user, $count, $semester);
                Mail::to($user)->send($message);
                $this->line("Message sent to: {$user->display_name}");
            }
            else{
                $this->line("Error - User: {$user->display_name}");
            }
            
            
        }

        return Command::SUCCESS;
    }
}
