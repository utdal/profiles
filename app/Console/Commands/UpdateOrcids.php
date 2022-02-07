<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Profile;
use App\ProfileData;


class UpdateOrcids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orcid:update';

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
        $profiles = Profile::whereHas('data', function ($query) {
            $query->where('type', 'information')
                ->where('data->orc_id_managed', '1')
                ->whereNotNull('data->orc_id');
        })->get();

        Log::notice("Starting scheduled ORCiD data update for {$profiles->count()} profiles... ");

        foreach ($profiles as $profile) {
            if ($profile->updateORCID()) {
                $inc++;
                Log::info("Updated ORCiD info for {$profile->full_name}");
            }
            else {
                Log::error("An error has occurred updating ORCiD info for {$profile->full_name},
                profile_id: {$profile->id}, orc_id: {$profile->data->orc_id}");
                $this->error("An error has occurred updating ORCiD info for {$profile->full_name},
                profile_id: {$profile->id}, orc_id: {$profile->data->orc_id}");
            }
        }
        Log::notice("Completed: {$inc}/{$profiles->count()} profiles have been updated.");
        $this->info("Completed: {$inc}/{$profiles->count()} profiles have been updated. 
        See application log for further details.");
        return Command::SUCCESS;
    }
}
