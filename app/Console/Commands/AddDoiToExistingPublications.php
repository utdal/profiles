<?php

namespace App\Console\Commands;

use App\Profile;
use App\ProfileData;
use App\Providers\AAPublicationsApiServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
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

        $profiles = Cache::remember(
            "profiles-starting-with-{$starting_character}",
            15 * 60,
            fn() => $this->profilesMissingDoi($starting_character)
        );

        $profiles_bar = $this->output->createProgressBar(count($profiles));
        $profiles_bar->setFormat('debug');
        $profiles_bar->start();

        $this->lineAndLog("********** {$profiles->count()} Profiles found with publications without DOI ************ \n");

        foreach ($profiles as $profile) {

            $profile_publications = $profile->data;

            $publications_bar = $this->output->createProgressBar(count($profile->data));
            $publications_bar->setFormat('debug');
            $publications_bar->start();

            $pubs_service_provider = App::make(AAPublicationsApiServiceProvider::class);
            $aa_publications = $pubs_service_provider
                                    ->getCachedPublications($profile->id, $profile->academic_analytics_id);

            $publications_found_in_url = $publications_found_in_title = $publications_found_in_aa = $doi_not_found_counter = 0;

            $this->lineAndLog("********** {$profile_publications->count()} Publications found without DOI for {$profile->full_name} ************\n");

            foreach ($profile_publications as $publication) {

                $this->lineAndLog(  "--------- {$publication->id} -----------\n");

                $doi = $aa_title = null;

                    // Search in the URL
                    if (!empty($publication->url)) {
                        $this->lineAndLog("Searching for DOI in URL...");
                        $doi = $this->validateDoiRegex($publication->url);

                        $this->verifyAndSaveDoi($publication, $doi, 'url', $publications_found_in_url, $publications_found_in_title, $publications_found_in_aa);

                    }

                    // Search in the title
                    if (is_null($doi) && !empty($publication->title)) {
                        $this->lineAndLog("Searching for DOI in the title...");
                        $doi = $this->validateDoiRegex(strip_tags(html_entity_decode($publication->title)));

                        $this->verifyAndSaveDoi($publication, $doi, 'title', $publications_found_in_url, $publications_found_in_title, $publications_found_in_aa);

                    }

                    // Match the AA Publication
                    if (is_null($doi)) {
                        $this->lineAndLog("Searching for DOI in Academic Analytics...");

                        $aa_pub_found = ProfileData::searchPublicationByTitleAndYear(
                                                $publication->title,
                                                $publication->year,
                                                $aa_publications ??
                                                $pubs_service_provider->getCachedPublications($profile->id, $profile->academic_analytics_id));

                        $doi = $aa_pub_found[0];
                        $aa_title = $aa_pub_found[1];

                        $this->verifyAndSaveDoi($publication, $doi, 'Academic Analytics', $publications_found_in_url, $publications_found_in_title, $publications_found_in_aa, $aa_title);
                    }

                    if (is_null($doi)) { $doi_not_found_counter++; }

                    $publications_bar->advance();
                }

                $publications_bar->finish();

                $this->lineAndLogResults($publications_found_in_url, $publications_found_in_title, $publications_found_in_aa, $doi_not_found_counter, $profile->full_name);

                $profiles_bar->advance();

            }

        $profiles_bar->finish();

        return Command::SUCCESS;
    }

    /**
     * Verify given doi. If the doi is not null, updates the profile_data record and increment the counter of doi's found.
     */
    public function verifyAndSaveDoi($publication, $doi, $search_field, &$publications_found_in_url, &$publications_found_in_title, &$publications_found_in_aa, $aa_title = null)
    {

        if (is_null($doi)) {
            $this->lineAndLog("DOI not found in {$search_field}.");
        }
        else {

            $publication->updateData(['doi' => $doi]);
            if (!is_null($aa_title)) {
                $publication->insertData(['aa_title' => $aa_title]);
                $this->log("Title {$publication->title} found as {$aa_title} in Academic Analytics Publications\n.");
            }

            $publication->save();
            $this->lineAndLog("DOI {$doi} FOUND IN {$search_field} and updated.");

            switch ($search_field)
            {
                case 'title':
                    ++$publications_found_in_title;
                    break;
                case 'url':
                    ++$publications_found_in_url;
                    break;
                case 'Academic Analytics':
                    ++$publications_found_in_aa;
                    break;
            }

        }

    }

    /**
     * Validate DOI regex
     * @return String
     */
    public function validateDoiRegex($doi_expression)
    {
        $doi_regex = config('app.doi_regex') ?? '/(10[.][0-9]{4,}[^\s"\/<>]*\/[^\s"<>]+)/';

        $doi = null;

        preg_match($doi_regex, $doi_expression, $matches);

        if (!empty($matches[1])) {
            $doi = rtrim(trim($matches[1], "\xC2\xA0"), '.');
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
        return Profile::LastNameStartWithCharacter($starting_character)
                        ->withWhereHas('data', function($q) {
                                      $q->where('type', 'publications')
                                        ->whereNull('data->doi');
        })->get();
    }

    /**
     * Output total numbers for each profile processed to the console and log file
     */
    public function lineAndLogResults($publications_found_in_url, $publications_found_in_title, $publications_found_in_aa, $doi_not_found_counter, $profile_full_name)
    {
        $this->lineAndLog("TOTAL:");
        if ($publications_found_in_url > 0 ) { $this->lineAndLog("{$publications_found_in_url} DOI found by url and added successfully."); }
        if ($publications_found_in_title > 0 ) { $this->lineAndLog("{$publications_found_in_title} DOI found by title and added successfully."); }
        if ($publications_found_in_aa > 0 ) { $this->lineAndLog("{$publications_found_in_aa} DOI found in Academic Analytics and added successfully."); }
        $this->lineAndLog("{$doi_not_found_counter} PUBLICATIONS NOT FOUND.", 'error');
        $this->lineAndLog("***************** End of Report for {$profile_full_name}  ********************");
    }

    /**
     * Output a message to the console and log file
     */
    public function lineAndLog(string $message, string $type = 'info'): void
    {
        $this->line($message, $type);
        Log::$type($message);
    }

    /**
     * Output a message to the log file
     */
    public function Log(string $message, string $type = 'info'): void
    {
        Log::$type($message);
    }
}
