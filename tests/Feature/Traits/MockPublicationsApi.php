<?php

namespace Tests\Feature\Traits;

use App\Profile;
use App\ProfileData;
use App\Providers\AAPublicationsApiServiceProvider;
use Mockery;
use Illuminate\Database\Eloquent\Factories\Sequence;
trait MockPublicationsApi
{

   public function mockPublicationsServiceProvider(&$profile, $expected_res, $publications = null)
    {
        $pub_service_provider_mock = Mockery::mock(AAPublicationsApiServiceProvider::class);

        $pub_service_provider_mock
                ->makePartial()
                ->shouldReceive('getPersonId')
                ->andReturnUsing(function () use (&$profile, $expected_res) {
                    if (!isset($profile->information->first()->data['academic_analytics_id'])) {
                        $profile->information()->first()->updateData(['academic_analytics_id' => $expected_res]);
                        return $expected_res;
                    }
                else {
                    return $profile->information()->first()->data['academic_analytics_id']; }
                })
                ->shouldReceive('getCachedPublications')
                ->andReturn($publications)
                ->getMock();

        return $pub_service_provider_mock;
    }

    public function mockProfile()
    {
        $profile = Profile::factory()
                            ->hasData()
                            ->has(ProfileData::factory()
                                ->count(5)
                                ->state(function(){
                                    return ['type' => 'publications', 'data->doi' => '10.1000/123456'];
                                })
                                ->general(),'data')
                            ->create();

        $expected_res = 1234;

        $this->mockPublicationsServiceProvider($profile, $expected_res)->getPersonId($profile->user->email);

        return $profile;
    }

    public function mockPublications($count, $title, $year) {

        return ProfileData::factory()
                            ->count($count)
                            ->sequence(
                                    ['data->title' => $title], //Similar = True
                                    ['data->title' => $title . ' ' . $this->faker->words(1, true)], //Similar = True
                                    ['data->title' => $this->faker->words(1, true) . ' ' . $title], //Similar = True
                                    ['data->title' => $this->faker->sentence()], //Similar = False
                                    ['data->title' => $this->faker->words(2, true)  . ' ' . $title . ' ' . $this->faker->words(2, true) ], //Similar = False
                                )
                            ->sequence(
                                    ['data->doi' => '10.1000/abc123xyz.24'],
                                    ['data->doi' => '10.1038/issn.1476-4687'],
                                    ['data->doi' => '10.1111/dome.12082'],
                                    ['data->doi' => '10.1111/josi.12122'],
                                )
                            ->state(new Sequence (fn ($sequence) => ['id' => $sequence->index]))
                            ->make([
                                    'type' => 'publications',
                                    'sort_order' => $year,
                                    'data->url' => $this->faker->url(),
                                    'data->year' => $year,
                                    'data->type' => 'Journal',
                                    'data->status' => 'published',
                                    'imported' => false,
                                 ]);
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
