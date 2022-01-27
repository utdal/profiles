<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Profile;
use App\ProfileData;


class UpdateOrchids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orchid:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loop through the profiles with orcid managed publications to sync the info';

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
        $inc = 0;
        $profiles_to_update = Profile::with('data')->get()->filter(function($profile, $key){
            return $profile->hasOrcidManagedPublications();
        });

        foreach($profiles_to_update as $profile) {
            if ($profile->updateORCID()) {
                $inc++;
            }
            else {
                $this->error('An error has occurred');
            }
        }

        $this->info('Success: '.$inc .' profiles have been updated.');
        return Command::SUCCESS;
    }
}
