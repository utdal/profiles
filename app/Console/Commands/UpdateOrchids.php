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
        $profiles = Profile::whereHas('data', function($query) { $query->where('type', 'information')->where('data->orc_id_managed', '1'); } )->get();

        foreach($profiles as $profile) {
            if ($profile->updateORCID()) {
                $inc++;
                $this->info("Updated ORCiD info for {$profile->full_name}");
            }
            else {
                $this->error('An error has occurred updating ORCiD info for ' . $profile->full_name);
            }
        }

        $this->info("Success: {$inc} profiles have been updated.");
        return Command::SUCCESS;
    }
}
