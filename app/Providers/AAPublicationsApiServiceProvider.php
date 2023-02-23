<?php

namespace App\Providers;

use App\ProfileData;
use App\Interfaces\PublicationsApiInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
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
     *
     */
    public function getPersonId($client_faculty_id): int|false
    {
        $url = "https://api.academicanalytics.com/person/GetPersonIdByClientFacultyId?clientFacultyId=$client_faculty_id";

        $datum = $this->sendRequest($url);

        return !$datum ?: $datum['PersonId'];
    }

    /**
     * {@inheritdoc}
     */
    public function getPublications(int $faculty_id): Collection
    {
        $publications = new Collection();

        $url = "https://api.academicanalytics.com/person/" . $faculty_id . "/articles";

        $datum = $this->sendRequest($url);

        if (!$datum) {
            return false;
        }
        else {
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
    }

    /**
     *  {@inheritdoc}
     */
    public function getCachedPublications($profile_id, $academic_analytics_id): Collection
    {
        return Cache::remember(
            "profile{$profile_id}-AA-pubs",
            15 * 60,
            fn() => $this->getPublications($academic_analytics_id)
        );
    }

    /**
     * {@inheritdoc}
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

    public function getHttpClient(): Client
    {
        return $this->client;
    }

}
