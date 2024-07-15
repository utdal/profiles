<?php

namespace Tests\Feature;

use App\Profile;
use App\ProfileData;
use Tests\TestCase;
use App\Helpers\Publication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\LoginWithRole;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\MockPublicationsRepository;
use App\Repositories\OrcidPublicationsRepository;
use Tests\Feature\Traits\HasJson;

class PublicationsRepositoryTest extends TestCase
{
    use MockPublicationsRepository;
    use RefreshDatabase;
    use WithFaker;
    use LoginWithRole;
    use HasJson;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;
    public array $existing_publications_data;
    public Profile $profile;

    /** @test 
    ** @group orcid_import
    **/
    public function testImportOrcidPublications()
    {
        $this->profile = Profile::factory()
                            ->hasData([
                                'data->orc_id_managed' => 1,
                                'data->orc_id' => $this->faker()->numerify(),
                            ])
                            ->has(
                                ProfileData::factory() //count = 3
                                ->existing_publication('general'),
                                'data')
                            ->has(
                                ProfileData::factory()
                                ->count(2)
                                ->state([
                                    'type' => 'publications',
                                    'data->sort_order' => $this->faker->year()
                                ])
                                ->general(), 'data')
                            ->create();

        $this->assertTrue($this->profile->hasOrcidManagedPublications());

        $this->assertCount(5, $this->profile->publications);
        $this->assertDatabaseCount('profile_data', 6);

        // $this->output("PROFILE PUBLICATIONS CREATED", $this->profile->publications, ['profile_id', 'sort_order', 'title']);

        $publications_edit_route = route('profiles.edit', [
            'profile' => $this->profile,
            'section' => 'publications',
        ]);

        $orcid_pubs_repo = $this->mockPublicationsRepository();

        $this->instance(OrcidPublicationsRepository::class, $orcid_pubs_repo);
        $this->loginAsAdmin();

        $this->followingRedirects()
            ->get($publications_edit_route)
            ->assertStatus(200)
            ->assertViewIs('profiles.show')
            ->assertSee('Publications updated via ORCID.');
       
        $this->profile->refresh();
        $this->assertCount(9, $this->profile->publications);
        $this->assertDatabaseCount('profile_data', 10);

        foreach ($this->profile->publications as $orcid_pub) {
            $this->assertDatabaseHas(
                'profile_data', 
                ['data' => $this->castToJson((array)$orcid_pub->data)]
            );

            if (isset($orcid_pub->data['authors'])) {

                $authors = Publication::formatAuthorsNames($orcid_pub->data['authors']);

                foreach ($authors as $author) {
                    $this->assertMatchesRegularExpression(Publication::REGEX_PATTERNS['APA'], $author['APA']);
                    
                }
            }
       }
    }

    /**
     * Output a message to the console and log file
     */
    public function output(string $message, $items = null, $attributes = null ): void
    {
        echo "\n $message \n";

        if (!is_null($items)) {
            
            foreach ($items as $key => $item) {
                $string = "$key ";
                foreach ($attributes as $attr) {
                    $string .= $item->$attr . " ";
                }
                $string .= "\n";
                echo $string;
            }
        }
    }
}