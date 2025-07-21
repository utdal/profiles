<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Profile;


class UpdateOrcids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profiles:update-orcid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update ORCID publications for sync-enabled profiles';

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
        $inc = $updated_total = $created_total = $similar_found_total = 0;

        $profiles = Profile::whereHas('data', function ($query) {
            $query->where('type', 'information')
                ->where('data->orc_id_managed', '1')
                ->whereNotNull('data->orc_id');
        })->get();

        $this->lineAndLog("Starting scheduled ORCiD data update for {$profiles->count()} profiles... \n");

        foreach ($profiles as $profile) {
            [$completed, $created, $updated, $similar_found] = $profile->updateORCID();

            if ($completed) {
                $inc++;
                $updated_total = $updated_total + $updated;
                $created_total = $created_total + $created;
                $similar_found_total = $similar_found_total + $similar_found;

                $this->lineAndLog("Updated ORCiD info for {$profile->full_name}");
            }
            else {
                $this->lineAndLog("An error has occurred updating ORCiD info for {$profile->full_name}, profile_id: {$profile->id}, profile: {$profile->url}", 'error');
            }
        }

        $this->lineAndLog("Completed: {$inc}/{$profiles->count()} profiles have been updated.");
        $this->lineAndLog("TOTAL: {$updated_total} publications have been updated. \n");
        $this->lineAndLog("TOTAL: {$created_total} publications have been created. \n");
        $this->lineAndLog("TOTAL: {$similar_found_total} similar publications have been found. \n");

        return Command::SUCCESS;
    }

    /**
     * Output a message to the console and log file
     */
    public function lineAndLog(string $message, string $type = 'info'): void
    {
        $this->line($message, $type);
        Log::$type($message);
    }
}
