<?php

namespace App\Providers;

//use App\ApiClientInterface;
use App\Profile;
use Illuminate\Support\ServiceProvider;


class OrcidApiService extends ServiceProvider //implements ApiClientInterface
{
    protected $orc_id, $authorization;

    public function __construct(Profile $profile)
    {
        $this->orc_id = $profile->information()->get(array('data'))->toArray()[0]['data']['orc_id'];
        $this->authorization = 'Bearer ' . config('ORCID_TOKEN');
    }

    public function getData()
    {
        $url = "https://pub.orcid.org/v2.0/" . $this->orc_id .  "/activities";

        $client = new HttpClientServiceProvider;

        $res = $client->send_request(['url' => $url, 'Authorization' => $this->authorization]);

        //an error of some sort
        if($res->getStatusCode() != 200){
          return false;
        }

        return json_decode($res->getBody()->getContents(), true);
    }

}
