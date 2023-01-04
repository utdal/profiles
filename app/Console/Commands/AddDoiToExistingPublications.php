<?php

namespace App\Console\Commands;

use App\Profile;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class AddDoiToExistingPublications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profiles:add-doi {starting_character : The first letter of the last name of the profiles to run command for.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search the DOI to add it to the existing publications for the profiles with the first letter of their last name given.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $starting_character = (string)$this->argument('starting_character');

        $profiles_processed_count = 0;

         $profiles = Cache::remember(
            "profiles-starting-with-{$starting_character}",
            15 * 60,
            fn() => $this->profilesMissingDoi($starting_character)
        );

        $this->lineAndLog("********** {$profiles->count()} Profiles found with publications without DOI ************");

        foreach ($profiles as $profile) {

            $profile_publications = $profile->publications()->whereNull('data->doi')->get();

            $aa_publications = null;
            $publications_found_in_url = $publications_found_in_title = $publications_found_in_aa = $doi_not_found_counter = 0;

            $this->lineAndLog("********** {$profile_publications->count()} Publications found without DOI for {$profile->full_name} ************");

            foreach ($profile_publications as $publication) {

                $this->lineAndLog("--------- {$publication->id} -----------");

                $doi = $aa_title = null;

                if (!empty($publication->url)) { // Search in the URL
                    $this->lineAndLog("Searching DOI in URL");

                    $doi = $this->validateDoiRegex($publication->url);

                    $this->lineAndlogDoiResult($doi, "url");

                    $publications_found_in_url = is_null($doi) ? $publications_found_in_url : ++$publications_found_in_url;
                }

                if (!empty($publication->title) and (is_null($doi))) { // Search in the title
                    $this->lineAndLog("Searching DOI in the title");

                    $doi = $this->validateDoiRegex(strip_tags(html_entity_decode($publication->title)));

                    $this->lineAndlogDoiResult($doi, "title");

                    $publications_found_in_title = is_null($doi) ? $publications_found_in_title : ++$publications_found_in_title;

                }

                if (is_null($doi)) { // Match the AA Publication
                    $aa_pub_found = $profile->searchPublicationByTitleAndYear(
                            $publication->title,
                            $publication->year,
                            $aa_publications ?? $profile->cachedAAPublications()
                    );
                    $doi = $aa_pub_found[0];
                    $aa_title = $aa_pub_found[1];

                    $this->lineAndlogDoiResult($doi, "Academic Analytics");

                    $publications_found_in_aa = is_null($doi) ? $publications_found_in_aa : ++$publications_found_in_aa;

                }

                if (is_null($doi)) { // Update and save publication
                    $doi_not_found_counter++;
                }
                else {
                    $publication->updateData(['doi' => $doi]);
                    if (!is_null($aa_title)) {
                        $publication->insertData(['aa_title' => $aa_title]);
                    }
                    $publication->save();
                    $this->lineAndLog("DOI {$doi} updated.");
                }

            }

            $profiles_processed_count++;
            $this->lineAndLogResults($publications_found_in_url, $publications_found_in_title, $publications_found_in_aa, $doi_not_found_counter, $profile->full_name, $profiles_processed_count, $profiles->count());

        }

        return Command::SUCCESS;
    }

    /**
     * Validate DOI regex
     * @return String
     */
    public function validateDoiRegex($doi_expression)
    {
        $doi_regex= '/(10[.][0-9]{4,}[^\s"\/<>]*\/[^\s"<>]+)/';

        $doi = null;

        preg_match($doi_regex, $doi_expression, $result);

        if (!empty($result[1])) {
            $doi = rtrim(trim($result[1], "\xC2\xA0"), '.');
        }

        return $doi;
    }

    /**
     * Retrieves profiles with publications that have DOI missing
     *
     * @return Collection
     */
    public function profilesMissingDoi(string $starting_character)
    {
        return Profile::where('last_name', 'like', strtolower($starting_character).'%')
                    ->orWhere('last_name', 'like', strtoupper($starting_character).'%')
                    ->whereHas('data', fn($q) => $q->whereNull('data->doi'))
                    ->with('data')->get();
    }

    /**
     * Output results for each publication to the console and log file
     */
    public function lineAndlogDoiResult($doi, $search_field): void
    {
        if (is_null($doi)) {
            $this->lineAndLog("DOI not found in {$search_field}.");
        }
        else {
            $this->lineAndLog("DOI {$doi} FOUND IN {$search_field}.");
        }
    }

    /**
     * Output total numbers for each profile processed to the console and log file
     */
    public function lineAndLogResults($publications_found_in_url, $publications_found_in_title, $publications_found_in_aa, $doi_not_found_counter, $profile_full_name, $profiles_processed_count, $profiles_count)
    {
        $this->lineAndLog("TOTAL: \n {$publications_found_in_url} DOI found by url and added successfully.");
        $this->lineAndLog("{$publications_found_in_title} DOI found by title and added successfully.");
        $this->lineAndLog("{$publications_found_in_aa} DOI found in Academic Analytics and added successfully.");
        $this->lineAndLog("{$doi_not_found_counter} PUBLICATIONS NOT FOUND.", 'error');
        $this->lineAndLog("***************** End of Report for {$profile_full_name}  ********************");
        $this->lineAndLog("{$profiles_processed_count} Profiles scanned out of {$profiles_count}.");
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
