<?php

namespace App\Repositories;

use App\ProfileData;
use App\Profile;
use App\Repositories\Contracts\PublicationsRepositoryContract;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class OrcidPublicationsRepository implements PublicationsRepositoryContract
{
    protected Client $client;
    protected Profile $profile;

    public function __construct(Profile $profile)
    {
        $this->client = New Client();
        $this->profile = $profile;
    }

    /**
     * Receive an attribute to get from the API the identifier necessary to retrieve the publications
     * @param string $client_faculty_id
     * @return mixed|true
     */
    public function getPersonId($client_faculty_id)
    {
        $orc_id = $this->profile->information()->get(array('data'))->toArray()[0]['data']['orc_id'];

        return $orc_id ?? false;
    }

    public function getPublications2(int $orc_id)
    {
        /** @var Collection<int, ProfileData> */
        $publications = new Collection();

        $orc_id = $this->profile->information()->get(array('data'))->toArray()[0]['data']['orc_id'];
        
        if (is_null($orc_id)) {
            return false;
        }

        $orc_url = "https://pub.orcid.org/v2.0/" . $orc_id .  "/works";

        $res = $this->makeApiRequest($orc_url);

        $putcodes = [];
       
        $grouped_works = collect(json_decode($res->getBody()->getContents(), true)['group'])->pluck('work-summary');

        $putcodes = $grouped_works->map(function ($item, $key) {
                                        return collect($item)->sortByDesc('display-index')->value('put-code');
                                    })->toArray();

        $split_putcodes = array_chunk($putcodes, 100);

        foreach ($split_putcodes as $putcodes_set) {

            $string_put_codes = implode(',', $putcodes_set);

            $multiple_works_url = "https://pub.orcid.org/v2.0/" . $orc_id .  "/works/". $string_put_codes;

            $res2 = $this->makeApiRequest($multiple_works_url);

            $works_data = json_decode($res2->getBody()->getContents(), true)['bulk'];

            foreach ($works_data as $record) {

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
                
                $contributors_array = count($contributors->toArray()) > 0 ? $contributors->toArray() :[$this->full_name];
                $authors = $this->formatAuthors($contributors_array);

                $url = $record['work']['url']['value'] ?? ($doi_url ?? ($eid_url ?? null));

                $profile_data = ProfileData::firstOrCreate([
                    'profile_id' => $this->id,
                    'type' => 'publications',
                    'data->title' => $record['work']['title']['title']['value'],
                    'sort_order' => $record['work']['publication-date']['year']['value'] ?? null,
                ],[
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
                        'apa_formatted_authors' => ProfileData::formatAuthorsApa($authors),
                        'status' => 'Published',
                        'citation-type' => $record['work']['type'] ?? null,
                        'citation-value' => $record['work']['value'] ?? null,
                        'visibility' => $record['work']['visibility'],
                    ],
                ]);
            }
        }

      Cache::tags(['profile_data'])->flush();

      //ran through process successfully
      return true;
    }

    /**
     * Retrieve the publications from the API to return a ProfileData model collection
     *  @param int $faculty_id
     */
    public function getPublications(int $faculty_id)
    {
        /** @var Collection<int, ProfileData> */
        $publications = new Collection();

        $url = "https://api.academicanalytics.com/person/" . $faculty_id . "/articles";

        $datum = $this->sendRequest($url);

        foreach($datum as $record) {
            $url = NULL;

            if(isset($record['DOI'])) {
                $doi = $record['DOI'];
                $url = "http://doi.org/$doi";
            }

            $new_record = ProfileData::newModelInstance([
                'type' => 'publications',
                'sort_order' => $record['ArticleYear'] ?? null,
                'data' => [
                    'doi' => $doi ?? null,
                    'url' => $url ?? null,
                    'title' => $record['ArticleTitle'],
                    'year' => $record['ArticleYear'] ?? null,
                    'type' => "JOURNAL_ARTICLE", //ucwords(strtolower(str_replace('_', ' ', $record['work-summary'][0]['type']))),
                    'status' => 'Published'
                ],
            ]);
            $new_record->id = $record['ArticleId'];
            $new_record->imported = false;
            $publications->push($new_record);
        }
        return $publications;
    }

    /**
     * Cache publications for the current profile
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCachedPublications($profile_id, $academic_analytics_id)
    {
        return Cache::remember(
            "profile{$profile_id}-AA-pubs",
            15 * 60,
            fn() => $this->getPublications($academic_analytics_id)
        );
    }

    /**
     * Make a get request to the API
     */
    public function sendRequest(string $url): array|false
    {
        $response = $this->getHttpClient()->get($url, [
            'headers' => [
                'apikey' => config('app.academic_analytics_key'),
                'Accept' => 'application/json'
            ],
            'http_errors' => false, // don't throw exceptions for 4xx,5xx responses
        ]);

        if($response->getStatusCode() != 200){
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