<?php

namespace App\Providers;

use App\Profile;
use App\ProfileData;
use App\Interfaces\PublicationsApiInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\ServiceProvider;


class PublicationsApiServiceProvider extends ServiceProvider implements PublicationsApiInterface
{
    protected $academic_analytics_id;

    public $profile;
    public Client $client;

    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
        $this->academic_analytics_id = $this->getAcademicAnalyticsId($this->profile);
        $this->client = new Client();
    }

    public function getAcademicAnalyticsId()
    {
        if(isset($this->profile->information()->first()->data['academic_analytics_id'])) {
            return $this->profile->information()->first()->data['academic_analytics_id'];
        }
        else {
            $academic_analytics_id = $this->getAAPersonId();
            $this->profile->information()->update(['data->academic_analytics_id' => $academic_analytics_id]);
            $this->profile->save();
            return $academic_analytics_id;
        }
    }

    public function getAAPersonId(): int|false
    {
        $client_faculty_id = "{$this->profile->user->name}@utdallas.edu";

        $url = "https://api.academicanalytics.com/person/GetPersonIdByClientFacultyId?clientFacultyId=$client_faculty_id";

        $res = $this->sendRequest($url);

        //an error of some sort
        if($res->getStatusCode() != 200){
            return false;
        }
        $datum = json_decode($res->getBody()->getContents(), true);

        return $datum['PersonId'];
    }

    public function getAcademicAnalyticsPublications()
    {
        $url = "https://api.academicanalytics.com/person/" . $this->academic_analytics_id . "/articles";

        $res = $this->sendRequest($url);

        //an error of some sort
        if($res->getStatusCode() != 200){
            return false;
        }
        $datum = json_decode($res->getBody()->getContents(), true);

        $publications = collect();

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

    public function sendRequest($url): Response
    {
        return $this->getHttpClient()->get($url, [
          'headers' => [
            'apikey' => config('app.academic_analytics_key'),
            'Accept' => 'application/json'
          ],
          'http_errors' => false, // don't throw exceptions for 4xx,5xx responses
        ]);
    }

    public function getHttpClient()
    {
        return $this->client;
    }

}
