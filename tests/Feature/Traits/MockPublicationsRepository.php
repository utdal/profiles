<?php

namespace Tests\Feature\Traits;

use App\ProfileData;
use App\Repositories\OrcidPublicationsRepository;

trait MockPublicationsRepository
{
    /**
     * Partial mock to return the orcid API response containing publications (ProfileData collection)
     * 
     * @return OrcidPublicationsRepository
     */
   public function mockPublicationsRepository()
    {
        $publications = $this->makePublications();

       // $this->output("API PUBLICATIONS TO SYNC", $publications, ['profile_id', 'sort_order', 'title']);

        $pubs_mock = mock(OrcidPublicationsRepository::class)->makePartial();

        $pubs_mock
            ->shouldReceive('getCachedPublications')
            ->andReturn($publications);

        return $pubs_mock;
    }

    /** 
     * Returns a ProfileDataFactory collection of publications exisisting in the DB and new publications
     * 
     * @return \Illuminate\Support\Collection<ProfileDataFactory>
     */
    public function makePublications()
    {
        $orcid_api_new_pubs = 
            ProfileData::factory()
                ->count(4)
                ->orcid_publication($this->profile)
                ->make();
        
        $orcid_api_existing_pubs =
            ProfileData::factory() //count = 3
                ->existing_publication('orcid_publication', $this->profile)
                ->make();

        $orcid_api_new_pubs->map(fn($pub) => $orcid_api_existing_pubs->push($pub));

        return $orcid_api_existing_pubs;
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
