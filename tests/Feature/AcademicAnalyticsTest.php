<?php

namespace Tests\Feature;

use App\Profile;
use App\Providers\AcademicAnalyticsApiServiceProvider;
use App\Providers\ApiClientServiceProvider;
use App\Providers\HttpClientInterface;
use App\Providers\HttpClientServiceProvider;
use Doctrine\DBAL\Types\IntegerType;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class AcademicAnalyticsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function testAddsDoi()
    {
        $profile = Profile::factory()->hasData()->create();

        $mock = Mockery::mock(ApiClientServiceProvider::class, function(MockInterface $mock) {
        $mock->shouldReceive('sendRequest')
                ->andReturn(new Response(
                    $status = 200,
                    $headers = []
                ));
                $mock->shouldReceive('getPersonAAId')->andReturn(false);


            });


            $mock->sendRequest()



       $x = new AcademicAnalyticsApiServiceProvider($mock, $profile);

       //var_dump($x);


/*         $client_faculty_id = "{$profile->user->name}@utdallas.edu";

        $url = "https://api.academicanalytics.com/person/GetPersonIdByClientFacultyId?clientFacultyId=$client_faculty_id";

        Http::fake([$url => Http::response()]);

        Http::get($url);

        $recorded = Http::recorded();

        var_dump($recorded); */
    }


}
