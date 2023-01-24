<?php

namespace Tests\Feature;

use App\Profile;
use App\Providers\PublicationsApiServiceProvider;
use Doctrine\DBAL\Types\IntegerType;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use Tests\Feature\Traits\MockPublicationsApi;

class AcademicAnalyticsTest extends TestCase
{
    use MockPublicationsApi;
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
        $profile = Profile::factory()->hasData()->make();


        $client_faculty_id = "{$profile->user->name}@utdallas.edu";

        $academic_analytics_id = $profile->information()->first()->data['academic_analytics_id'];
        $url = "https://api.academicanalytics.com/person/" . $academic_analytics_id . "/articles";
        $json_response = "{}";
        $aa_mock = $this->mockAcademicAnalytics($academic_analytics_id, $url, $json_response);

       // $this->assertEquals($academic_analytics_id, $aa_mock->getAcademicAnalyticsId(5));

    }


}
