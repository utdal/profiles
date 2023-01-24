<?php

namespace Tests\Feature\Traits;

use Mockery;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;

trait MockPublicationsApi
{

    protected function mockAcademicAnalytics($academic_analytics_id, $url, $json_response)
    {
        $http_client_mock = Mockery::mock(Client::class)
            ->shouldReceive('getStatusCode')->andReturn(200)
            ->shouldReceive('getBody->getContents')->andReturn($json_response);


        $this->partialMock(PublicationsApiServiceProvider::class, function($aa_mock) use ($academic_analytics_id, $url, $http_client_mock) {
            $aa_mock->shouldReceive('getAcademicAnalyticsId')
                    ->andReturn($academic_analytics_id)
                    ->shouldReceive('getAAPersonalId')
                    ->andReturn(false, $academic_analytics_id)
                    //->shouldReceive('getAcademicAnalyticsPublications')
                    //->andReturn($aa_publications)
                    ->shouldReceive('sendRequest')->with($url)->andReturn($http_client_mock)

                   ->shouldReceive('getHttpClient')
                   ->andReturn($http_client_mock);
        });

    }


    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        // fix for the config() helper not resolving in tests using Mockery
        $config = app('config');
        parent::tearDown();
        app()->instance('config', $config);
    }
}
