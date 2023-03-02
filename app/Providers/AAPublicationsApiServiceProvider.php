<?php

namespace App\Providers;

use App\ProfileData;
use App\Interfaces\PublicationsApiInterface;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AAPublicationsApiServiceProvider extends ServiceProvider implements PublicationsApiInterface
{
    public $academic_analytics_id;
    protected Client $client;

    public function __construct()
    {
        $this->client = New Client();
    }

    /**
     * Receive an attribute to get from the API the identifier necessary to retrieve the publications
     * @param string $client_faculty_id
     * @return mixed|true
     */
    public function getPersonId($client_faculty_id)
    {
        $url = "https://api.academicanalytics.com/person/GetPersonIdByClientFacultyId?clientFacultyId=$client_faculty_id";

        $datum = $this->sendRequest($url);

        return !$datum ?: $datum['PersonId'];
    }

    /**
     * Retrieve the publications from the API to return a ProfileData model collection
     *  @param int $faculty_id
     *  @return \Illuminate\Database\Eloquent\Collection|false
     */
    public function getPublications(int $faculty_id)
    {
        /** @var Collection<ProfileData> */
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
