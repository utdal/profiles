<?php

namespace App\Repositories;

use App\ProfileData;
use App\Profile;
use App\Repositories\Contracts\PublicationsRepositoryContract;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use App\Helpers\Publication;

class OrcidPublicationsRepository implements PublicationsRepositoryContract
{
    protected Client $client;
    public Profile $profile;

    public function __construct(Profile $profile)
    {
        $this->client = New Client();
        $this->profile = $profile;
    }

    /**
     * Receive an attribute to get from the API the identifier necessary to retrieve the publications
     * @param string $faculty_id
     * @return string|false
     */
    public function getPersonId($client_faculty_id = null)
    {

    }

    public function getPublications()
    {
        /** @var Collection<int, ProfileData> */
        $publications = new Collection();

        $orc_id = $this->profile->orcid;
        
        if (is_null($orc_id)) {
            return false;
        }

        $all_works_url = "https://pub.orcid.org/v2.0/" . $orc_id .  "/works";

        $all_works_data = $this->sendRequest($all_works_url);

        $putcodes = [];
       
        $grouped_works = collect($all_works_data)->pluck('work-summary');

        $putcodes = $grouped_works->map(function ($item, $key) {
                                        return collect($item)->sortByDesc('display-index')->value('put-code');
                                    })->toArray();

        $split_putcodes = array_chunk($putcodes, 100);

        foreach ($split_putcodes as $putcodes_set) {

            $string_put_codes = implode(',', $putcodes_set);

            $putcodes_works_url = "https://pub.orcid.org/v2.0/" . $orc_id .  "/works/". $string_put_codes;

            $putcodes_works_data = $this->sendRequest($putcodes_works_url)['bulk'];

            foreach ($putcodes_works_data as $record) {

                $url = $doi_url = $eid_url = null;

                foreach ($record['work']['external-ids']['external-id'] as $ref) {
                    if ($ref['external-id-type'] == "eid") {
                        $eid = $ref['external-id-value'];
                        $eid_url = "https://www.scopus.com/record/display.uri?origin=resultslist&eid=" . $ref['external-id-value'];
                    }
                    elseif ($ref['external-id-type'] == "doi") {
                        $doi = $ref['external-id-value'];
                        $doi_url = "http://doi.org/" . $ref['external-id-value'];
                    }
                }

                $contributors = collect($record['work']['contributors'])
                                ->flatten(1)
                                ->map(fn($author) => $author['credit-name']['value']);
                
                $contributors_array = count($contributors->toArray()) > 0 ? $contributors->toArray() :[$this->profile->full_name];
                
                $authors = Publication::formatAuthors($contributors_array);

                $url = $record['work']['url']['value'] ?? ($doi_url ?? ($eid_url ?? null));

                $profile_data = ProfileData::new([
                    'profile_id' => $this->profile->id,
                    'type' => 'publications',
                    'data->title' => $record['work']['title']['title']['value'],
                    'sort_order' => $record['work']['publication-date']['year']['value'] ?? null,
                    'data' => [
                        'put-code' => $record['work']['put-code'],
                        'url' => $url,
                        'title' => $record['work']['title']['title']['value'],
                        'year' => $record['work']['publication-date']['year']['value'] ?? null,
                        'month' => $record['work']['publication-date']['month']['value'] ?? null,
                        'day' => $record['work']['publication-date']['day']['value'] ?? null,
                        'type' => ucwords(strtolower(str_replace('_', ' ', $record['work']['type']))),
                        'journal_title' => $record['work']['journal-title']['value'] ?? null,
                        'doi'  => $doi ?? null,
                        'eid' => $eid ?? null,
                        'authors' => $authors,
                        'apa_formatted_authors' => Publication::formatAuthorsApa($authors),
                        'status' => 'Published',
                        'citation-type' => $record['work']['type'] ?? null,
                        'citation-value' => $record['work']['value'] ?? null,
                        'visibility' => $record['work']['visibility'],
                    ],
                ]);

                $publications->push($profile_data);
            }
            
            return $publications;
        }
    }

    /**
     * Retrieve the publications from the API to return a ProfileData model collection
     *  @param int $faculty_id
     */
    public function syncPublications()
    {
        $publications = $this->getCachedPublications();
        
        foreach($publications as $publication) {
            ProfileData::firstOrCreate([
                'profile_id' => $publication->profile_id,
                'type' => $publication->type,
                'data->title' => $publication->data['title'],
                'sort_order' => $publication->sort_order,
            ],
            [
                'data' => [
                    'put-code' => $publication->data['put-code'],
                    'url' => $publication->data['url'],
                    'title' => $publication->data['title'],
                    'year' => $publication->data['year'],
                    'month' => $publication->data['month'],
                    'day' => $publication->data['day'],
                    'type' => $publication->data['type'],
                    'journal_title' => $publication->data['journal_title'],
                    'doi'  => $publication->data['doi' ],
                    'eid' => $publication->data['eid'],
                    'authors' => $publication->data['authors'],
                    'apa_formatted_authors' => $publication->data['apa_formatted_authors'],
                    'status' => $publication->data['status'],
                    'citation-type' => $publication->data['citation-type'],
                    'citation-value' => $publication->data['citation-value'],
                    'visibility' => $publication->data['visibility'],
                ],
            ]);
        }

        Cache::tags(['profile_data'])->flush();

        //ran through process successfully
        return true;
    }

    /**
     * Cache publications for the current profile
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCachedPublications()
    {
        return Cache::remember(
            "profile{$this->profile->id}-orcid-pubs",
            15 * 60,
            fn() => $this->getPublications()
        );
    }

    public function sendRequest(string $url): array|false
    {
        $response = $this->getHttpClient()->get($url, [
                                'headers' => [
                                    'Authorization' => 'Bearer ' . config('ORCID_TOKEN'),
                                    'Accept' => 'application/json'
                                ],
                                'http_errors' => false, // don't throw exceptions for 4xx,5xx responses
                            ]);
        
        //Return false if the response returns an error
        if ($response->getStatusCode() != 200) {
            return false;
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Return the service provider http client
     */
    public function getHttpClient(): Client
    {
        return $this->client;
    }

}