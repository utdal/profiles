<?php

namespace Tests\Feature;

use App\Profile;
use App\ProfileData;
use Tests\TestCase;
use Tests\Feature\Traits\MockPublicationsApi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\LoginWithRole;
use Illuminate\Foundation\Testing\WithFaker;

class AcademicAnalyticsTest extends TestCase
{
    use MockPublicationsApi;
    use RefreshDatabase;
    use WithFaker;
    use LoginWithRole;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /** @test */
    public function testProfilesWithoutAcademicAnalyticsData()
    {
        $profiles = Profile::factory()
                            ->count(4)
                            ->hasData()
                            ->has(ProfileData::factory()
                                ->count(20)
                                ->sequence(['type' => 'publications'])
                                ->general(),'data')
                            ->create(['last_name' => 'Rogers']);

        $profiles_starting_with = Profile::LastNameStartWithCharacter("r")->get();

        $this->assertCount($profiles->count(), $profiles_starting_with);

        $publications_with_missing_doi = ProfileData::where('type', 'publications')
                                                    ->whereNull('data->doi')
                                                    ->distinct('profile_id')
                                                    ->get('profile_id');

        $profiles_with_missing_doi = Profile::LastNameStartWithCharacter("r")
                                            ->withWhereHas('data', function($q) {
                                                $q->where('type', 'publications')
                                                  ->whereNull('data->doi');
                                            })->get();

        $this->assertCount($profiles_with_missing_doi->count(), $publications_with_missing_doi);

        $expected_aa_id = 1234;

        foreach($profiles_with_missing_doi as $profile) {
            if (!isset($profile->information()->first()->data['academic_analytics_id'])) {
                $mock_aa_id = $this->mockPublicationsServiceProvider($profile, $expected_aa_id)->getPersonId($profile->email);
                $this->assertEquals($profile->information()->first()->data['academic_analytics_id'], $mock_aa_id);
            }
            $this->assertNotNull($profile->information()->first()->data['academic_analytics_id']);

            echo "\nAA ID is {$profile->information()->first()->data['academic_analytics_id']}\n";
        }
    }

    /** @test */
    public function testSearchPublication()
    {
        $title = "ABC Hello, how is it going ? 123";
        $year = 2018;
        $doi_regex = config('app.doi_regex') ?? '/(10[.][0-9]{4,}[^\s"\/<>]*\/[^\s"<>]+)/';

        $mock_aa_publications = $this->mockPublications(5, $title, $year);

        $similar = $different = 0;

        foreach($mock_aa_publications as $item) {
            $str_contains = str_contains(strtolower($title), strtolower(strip_tags(html_entity_decode($item->title)))) && $year==$item->year;
            $similar_text =similar_text(strtolower($title), strtolower(strip_tags(html_entity_decode($item->title))), $percent);
            $similar_text = (($percent > 80) && ($year==$item->year));

            if ($similar_text || $str_contains) {
                $this->assertTrue(($percent >= 80) && ($year == $item->year));
                $similar++;
            }
            if (($percent < 80) || ($year != $item->year)) {
                $this->assertFalse($similar_text);
                $this->assertFalse($str_contains);
                $different++;
            }

            preg_match($doi_regex, $item->doi, $matches);

            $this->assertNotEmpty($matches);
            $this->assertEquals(1, preg_match($doi_regex, rtrim(trim($matches[1], "\xC2\xA0"), '.')));
        }

        $this->assertGreaterThanOrEqual(60, $similar/5*100);
        $this->assertLessThanOrEqual(40, $different/5*100);
    }
}
