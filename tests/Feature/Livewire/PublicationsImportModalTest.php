<?php

namespace Tests\Feature;

use App\Http\Livewire\PublicationsImportModal;
use App\Providers\AAPublicationsApiServiceProvider;
use Tests\TestCase;
use Tests\Feature\Traits\MockPublicationsApi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\LoginWithRole;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;

class PublicationsImportModalTest extends TestCase
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
    public function testPublicationsImportModalComponent()
    {
        $expected_aa_id = 1234;
        $title = "ABC Hello, how is it going ? 123";
        $year = 2018;

        $profile = $this->mockProfile();
        $aa_publications = $this->mockPublications(15, $title, $year);
        $pub_service_provider_mock = $this->mockPublicationsServiceProvider($profile, $expected_aa_id, $aa_publications);

        $this->instance(AAPublicationsApiServiceProvider::class, $pub_service_provider_mock);

        $this->assertNotNull($profile->information()->first()->data['academic_analytics_id']);

        $route = route('profiles.edit', array_merge(['profile' => $profile->slug, 'section' => 'publications']));
        $user = $this->loginAsUserWithRole('profiles_editor');

        $this->assertTrue($user->can('update', $profile));

        $response = $this->followingRedirects()->get($route)
                    ->assertSessionHasNoErrors()
                    ->assertStatus(200)
                    ->assertViewIs('profiles.edit')
                    ->assertSeeLivewire('publications-import-modal');

        $filtered_aa_publications = $aa_publications->whereNotIn('doi', $profile->publications->pluck('data.doi')->filter()->values());

        $this->assertCount($filtered_aa_publications->count(), $aa_publications);

        $component = Livewire::test(PublicationsImportModal::class, ['profile' => $profile, 'modalVisible' => true]);

        $this->assertNotEmpty($component->publications);
        $this->assertEquals($component->publications[0]->title, $aa_publications->sortByDesc('sort_order')[0]->title);

        $component
            ->assertStatus(200)
            ->assertSet('modalVisible', true)
            ->assertSet('publications', $component->publications)
            ->assertSee($aa_publications[0]->title)
            ->assertSee($aa_publications[9]->title)
            ->emit('addToEditor', $component->publications[2]->id)
            ->assertHasNoErrors()
            ->assertSeeHtml('<input type="checkbox" class="remove-publication" id="'.$component->publications[2]->id.'" value="1" checked');
        $component
            ->emit('removeFromEditor', $component->publications[2]->id)
            ->assertSeeHtml('<input type="checkbox" class="add-publication" id="'.$component->publications[2]->id.'" value="0"');

        $this->assertNotContains($aa_publications[2]->id, $component->importedPublications);
        $this->assertFalse($component->publications[2]->imported);
        $this->assertNull($component->publications[10]);

        $component
            ->call('nextPage')
            ->assertSet('page', 2)
            ->assertSee($aa_publications[10]->title)
            ->assertSee($aa_publications[14]->title)
            ->emit('addToEditor', $component->publications[10]->id)
            ->assertHasNoErrors()
            ->assertSeeHtml('<input type="checkbox" class="remove-publication" id="'.$component->publications[10]->id.'" value="1" checked');

        $this->assertCount(1, $component->importedPublications);
        $this->assertContains($component->publications[10]->id, $component->importedPublications);
        $this->assertTrue($component->publications[10]->imported);
        $this->assertNull($component->publications[9]);

        $component
            ->emit('removeFromEditor', $component->publications[10]->id)
            ->assertSeeHtml('<input type="checkbox" class="add-publication" id="'.$component->publications[10]->id.'" value="0"')
            ->emit('addAllToEditor');

        foreach($component->publications as $pub){
            $component->assertSeeHtml('<input type="checkbox" class="remove-publication" id="'.$pub->id.'" value="1" checked');
            $this->assertTrue($pub->imported);
            $this->assertContains($pub->id, $component->importedPublications);
        }

        $component
            ->emit('removeFromEditor', $component->publications[14]->id)
            ->assertSeeHtml('<input type="checkbox" class="add-publication" id="'.$component->publications[14]->id.'" value="0"')
            ->emit('removeAllFromEditor');

        foreach($component->publications as $pub){
            $component->assertSeeHtml('<input type="checkbox" class="add-publication" id="'.$pub->id.'" value="0"');
            $this->assertFalse($pub->imported);
            $this->assertNotContains($pub->id, $component->importedPublications);
        }
    }

}
