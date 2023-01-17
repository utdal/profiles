<?php

namespace App\Providers;

//use App\ApiClientInterface;
use App\Profile;
use App\ProfileData;
use App\Providers\HttpClientServiceProvider;
use Illuminate\Support\ServiceProvider;

class AcademicAnalyticsApiService extends ServiceProvider //implements ApiClientInterface
{
    protected $academic_analytics_id;
    protected $apikey;
    protected Profile $profile;

    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
        $this->apikey = config('app.academic_analytics_key');
        $this->academic_analytics_id = $this->getAcademicAnalyticsId($this->profile);
    }

    public function getAcademicAnalyticsId()
    {
        if(isset($this->profile->information()->first()->data['academic_analytics_id'])) {
            return $this->profile->information()->first()->data['academic_analytics_id'];
        }
        else {
            $academic_analytics_id = $this->profile->getAAPersonId();
            $this->profile->information()->update(['data->academic_analytics_id' => $academic_analytics_id]);
            $this->profile->save();
        }
    }

    public function getAAPersonId()
    {
        $client_faculty_id = "{$this->profile->user->name}@utdallas.edu";

        $aa_url = "https://api.academicanalytics.com/person/GetPersonIdByClientFacultyId?clientFacultyId=$client_faculty_id";

        $client = new HttpClientServiceProvider;

        $res = $client->send_request(['url' => $aa_url, 'apikey' => $this->apikey]);

        //an error of some sort
        if($res->getStatusCode() != 200){
            return false;
        }
        $datum = json_decode($res->getBody()->getContents(), true);

        return $datum['PersonId'];
    }

    public function getAcademicAnalyticsPublications()
    {
        $client = new HttpClientServiceProvider;

        $aa_url = "https://api.academicanalytics.com/person/" . $this->academic_analytics_id . "/articles";

        $res = $client->send_request(['url' => $aa_url, 'apikey' => $this->apikey]);

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

}
