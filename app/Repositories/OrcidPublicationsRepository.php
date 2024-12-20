<?php

namespace App\Repositories;

use App\ProfileData;
use App\Profile;
use App\Repositories\Contracts\PublicationsRepositoryContract;
use GuzzleHttp\Client;
use \Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\Helpers\Publication;

class OrcidPublicationsRepository implements PublicationsRepositoryContract
{
    protected Client $client;
    public Profile $profile;

    public function __construct(Profile $profile)
    {
        $this->client = New Client();
    }

    /**
     * Receive an attribute to get from the API the identifier necessary to retrieve the publications
     * @param $faculty_id
     * @return false
     */
    public function getPersonId($faculty_id = null) : string|false
    {
        return false;
    }

    /**
     * Get the publications from the Orcid API to return a collection of ProfileData
     *  @return Collection<array-key, mixed>|false|null
     */
    public function getPublications() : Collection|false|null
    {
        /** @var Collection<array-key, mixed> */
        $publications = collect();

        $orc_id = $this->profile->orcid;
        
        if (is_null($orc_id)) {
            return false;
        }

        $putcodes = $this->getPublicationsCodes("https://pub.orcid.org/v2.0/" . $orc_id .  "/works");

        $split_putcodes = array_chunk($putcodes, 100);

        foreach ($split_putcodes as $putcodes_set) {

            $string_put_codes = implode(',', $putcodes_set);

            $putcodes_works_data = $this->sendRequest("https://pub.orcid.org/v2.0/$orc_id/works/$string_put_codes");

            foreach ($putcodes_works_data['bulk'] as $record) {

                $authors = $this->getPublicationAuthors($record, $this->profile->full_name);
                $references = $this->getPublicationReferences($record);

                $profile_data = new ProfileData([
                    'profile_id' => $this->profile->id,
                    'type' => 'publications',
                    'data->title' => $record['work']['title']['title']['value'],
                    'sort_order' => $record['work']['publication-date']['year']['value'] ?? null,
                    'data' => [
                        'put-code' => $record['work']['put-code'],
                        'url' => $references['url'],
                        'title' => $record['work']['title']['title']['value'],
                        'year' => $record['work']['publication-date']['year']['value'] ?? null,
                        'month' => $record['work']['publication-date']['month']['value'] ?? null,
                        'day' => $record['work']['publication-date']['day']['value'] ?? null,
                        'type' => ucwords(strtolower(str_replace('_', ' ', $record['work']['type']))),
                        'journal_title' => $record['work']['journal-title']['value'] ?? null,
                        'doi'  => $references['doi'],
                        'eid' => $references['eid'],
                        'authors' => $authors,
                        'authors_formatted' => [
                            'APA' => Publication::formatAuthorsApa($authors),
                        ],
                        'status' => 'Published',
                        'citation-type' => $record['work']['type'] ?? null,
                        'citation-value' => $record['work']['value'] ?? null,
                        'visibility' => $record['work']['visibility'],
                    ],
                ]);
                $publications->push($profile_data);
            }
        }
        return $publications;
    }

    /**
     * Sync collection of ProfileData publications
     *  @return bool
     */
    public function syncPublications() : bool
    {
        $publications = $this->getCachedPublications();
        
        foreach ($publications as $publication) {
            ProfileData::firstOrCreate([
                'profile_id' => $publication->profile_id,
                'type' => 'publications',
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
                    'authors_formatted' => $publication->data['authors_formatted'],
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
    * @return Collection
     */
    public function getCachedPublications() : Collection
    {
        return Cache::remember(
            "profile{$this->profile->id}-orcid-pubs",
            15 * 60,
            fn() => $this->getPublications()
        );
    }

    /** Make API request, return false if there's any error in the response 
     * @param string $url
     * @return array|false
     */
    public function sendRequest(string $url): array|false
    {
        $response = $this->client->get($url, [
                                'headers' => [
                                    'Authorization' => 'Bearer ' . config('ORCID_TOKEN'),
                                    'Accept' => 'application/json'
                                ],
                                'http_errors' => false, // don't throw exceptions for 4xx,5xx responses
                            ]);
        
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

    /**
     * Set profile property
     */
    public function setProfile(Profile $profile) : void
    {
        $this->profile = $profile;
    }

    /** 
     * Auxiliary method to obtain orcid publications putcodes
     * 
     * @param string $url
     * @return array
     */
    public function getPublicationsCodes(string $url) : array
    {
        $all_works_data = $this->sendRequest($url);

        $grouped_works = collect($all_works_data['group'])->pluck('work-summary');

        return $grouped_works->map(function ($item, $key) {
                                  return collect($item)->sortByDesc('display-index')->value('put-code');
                                })->toArray();
    }

    /** 
     * Return a orcid publication external references codes (doi and eid)
     * and, if the pub url is not present, it takes it from either doi or eid
     * 
     * @param array $record
     * @return array
     */
    public function getPublicationReferences(array $record) : array {
        $url = $doi_url = $eid_url = null;

        foreach ($record['work']['external-ids']['external-id'] as $ref) {
            if ($ref['external-id-type'] == "eid" && $ref['external-id-relationship'] === "SELF") {
                $eid = $ref['external-id-value'];
                $eid_url = "https://www.scopus.com/record/display.uri?origin=resultslist&eid=$eid";
            }
            elseif ($ref['external-id-type'] == "doi" && $ref['external-id-relationship'] === "SELF") {
                $doi = $ref['external-id-value'];
                $doi_url = "http://doi.org/$doi";
            }
        }

        $url = $record['work']['url']['value'] ?? ($doi_url ?? ($eid_url ?? null));

        return [
            'url' => $url,
            'doi' => $doi ?? null,
            'eid' => $eid ?? null,
        ];
    }

    /** 
     * Return array with the publication contributors' names
     * @param array $record
     * @return array
     */ 
    public function getPublicationAuthors(array $record, string $default_author_name) : array
    {
        $contributors = collect($record['work']['contributors'])
                        ->flatten(1)
                        ->map(fn($author) => $author['credit-name']['value']);
        
        /** @var array */
        $contributors_array = count($contributors) > 0 ? $contributors->toArray() : [$default_author_name];

        return $contributors_array;
    }
}