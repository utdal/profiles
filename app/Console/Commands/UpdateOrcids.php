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
        $total_profiles_count = $total_orcid_pubs_count = $updated_total = $created_total = 0;
        $exact_id_match_total = $contained_id_url_match_total = $exact_title_match_total = $contained_title_match_total = 0;
        $no_url_count_total = $similar_title_found_total = 0;

        $profiles = Profile::whereHas('data', function ($query) {
                        $query->where('type', 'information')
                            ->where('data->orc_id_managed', '1')
                            ->whereNotNull('data->orc_id');
                    })->get();

        $this->lineAndLog("Starting scheduled ORCiD data update for {$profiles->count()} profiles... \n");

        foreach ($profiles as $profile) {
            [
                $completed,
                $orcid_pubs_count,
                $created,
                $updated,
                $exact_id_match,
                $contained_id_url_match,
                $exact_title_match,
                $contained_title_match,
                $no_url_count,
                $similar_title_found,
            ] = $profile->updateORCID();

            if ($completed) {
                $total_profiles_count++;
                $total_orcid_pubs_count += $orcid_pubs_count;
                $created_total += $created;
                $updated_total += $updated;
                $exact_id_match_total += $exact_id_match;
                $contained_id_url_match_total += $contained_id_url_match;
                $exact_title_match_total += $exact_title_match;
                $contained_title_match_total += $contained_title_match;
                $no_url_count_total += $no_url_count;
                $similar_title_found_total += $similar_title_found;
                $this->lineAndLog("Updated ORCiD info for {$profile->full_name}");
            }
            else {
                $this->lineAndLog("An error has occurred updating ORCiD info for {$profile->full_name}, profile_id: {$profile->id}, profile: {$profile->url}", 'error');
            }
        }

        $this->lineAndLog("Completed: {$total_profiles_count}/{$profiles->count()} profiles have been updated.");
        $this->lineAndLog("TOTAL: {$updated_total} publications updated/{$total_orcid_pubs_count} orcid records found.");
        $this->lineAndLog("TOTAL: {$created_total} new publications created.");
        $this->lineAndLog("TOTAL: {$exact_id_match_total} publications found by exact ID.");
        $this->lineAndLog("TOTAL: {$contained_id_url_match_total} publications found by id contained in URL.");
        $this->lineAndLog("TOTAL: {$exact_title_match_total} publications found by exact title.");
        $this->lineAndLog("TOTAL: {$contained_title_match_total} publications found by title contained in existing record.");
        $this->lineAndLog("TOTAL: {$similar_title_found_total} similar publications have been found.");
        $this->lineAndLog("TOTAL: {$no_url_count_total} publications without URL.");

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
