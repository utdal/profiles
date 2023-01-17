<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class HttpClientServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function send_request(array $payload): Response
    {

        return app(Client::class)->get($payload['url'], [
          'headers' => [
            'Authorization' => $payload['Authorization'],
            'apikey' => $payload['apikey'],
            'Accept' => 'application/json'
          ],
          'http_errors' => false, // don't throw exceptions for 4xx,5xx responses
        ]);
    }


}
