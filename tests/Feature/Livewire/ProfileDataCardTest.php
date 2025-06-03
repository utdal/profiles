<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\ProfileDataCard;
use App\Profile;
use App\ProfileData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\Feature\Traits\LoginWithRole;
use Tests\TestCase;
use App\Enums\ProfileSectionType;

class ProfileDataCardTest extends TestCase
{
    use LoginWithRole;
    use RefreshDatabase;
    use WithFaker;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /**
    *  Test paginated items on the first and the last page
    * 
    *  @return void
    */
    public function testPaginatedItemsOnFirstAndLastPage()
    {
        $section_options = [ 'publications', 'presentations', 'projects', 'additionals' ];

        $profile = Profile::factory()
                            ->hasData()
                            ->has(ProfileData::factory()
                                ->count(30)
                                ->sequence(
                                    ['type' => 'presentations'],
                                    ['type' => 'publications'],
                                    ['type' => 'projects'],
                                    ['type' => 'additionals']
                                )
                                ->general(),'data')
                            ->create();
        
        $user = $this->loginAsUser();
        $editable = $user && $user->can('update', $profile);
        
        foreach ($section_options as $section_opt) {
            
            $section = ProfileSectionType::from($section_opt);

            $this->assertNotNull($section, 'Invalid section');

            $this->assertTrue(
                method_exists($profile, $section->value),
                'Profile does not have method '.$section->value
            );
            
            $section_data = $profile->{$section->value};
            $data_count = $section_data->count();
            $per_page = $section->perPage();

            $this->assertDatabaseHas('profile_data', ['type' => $section->value]);
            $this->assertIsIterable($section_data);
            $this->assertGreaterThanOrEqual($per_page, $data_count);

            $component = Livewire::test(ProfileDataCard::class, ['profile' => $profile, 'editable' => $editable, 'data_type' => $section_opt])
                        ->assertSet('data_type', $section_opt)
                        ->assertViewHas('data')
                        ->assertSeeHtmlInOrder(["<section id=\"$section_opt\" class=\"card\">", '<h3>', '<div class="entry">']);

            if ($section_opt === 'additionals') {
                $component->assertSee('Additional Information');
            } else {
                $component->assertSee(ucwords($section->value));
            }

            $first_page_items_count = $data_count >= $per_page ? $per_page : $data_count;
            
            $this->assertIsIterable($component->lastRenderedView->data);
            $this->assertCount($first_page_items_count, $component->lastRenderedView->data);

            $component->call('gotoPage', $component->lastRenderedView->data->lastPage(), $section_opt)
                        ->assertSet('data_type', $section_opt)
                        ->assertViewHas('data');
            
            $last_page_items_count = fmod($data_count, $per_page) > 0 ? fmod($data_count, $per_page) : $per_page;
            $this->assertIsIterable($component->lastRenderedView->data);
            $this->assertCount($last_page_items_count, $component->lastRenderedView->data);
        } 
    }

    /**
     *  Test paginated items on second page
     *
     *  @return void
     */
    public function testPaginatedItemsOnSecondPage()
    {
        $profile = Profile::factory()
                            ->hasData()
                            ->has(ProfileData::factory()
                                ->count(30)
                                ->awards(),'data')
                            ->has(ProfileData::factory()
                                ->count(30)
                                ->appointments(),'data')
                            ->has(ProfileData::factory()
                                ->count(20)
                                ->affiliations(),'data')
                            ->has(ProfileData::factory()
                                ->count(20)
                                ->support(),'data')
                            ->has(ProfileData::factory()
                                ->count(25)
                                ->news(),'data')
                            ->create();

        $sections = [ 'awards', 'appointments', 'news', 'affiliations', 'support' ];

        $route = route('profiles.show', array_merge(['profile' => $profile->slug], $sections));
        $user = $this->loginAsUser();
        $editable = $user && $user->can('update', $profile);
        
        $this->get($route)
            ->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertViewIs('profiles.show');
        
        foreach ($sections as $section) {
            $component = Livewire::test(ProfileDataCard::class, ['profile' => $profile, 'editable' => $editable, 'data_type' => $section ])
            ->assertHasNoErrors()
            ->assertViewIs("livewire.profile-data-cards.{$section}")
            ->call('nextPage');

            $this->assertDatabaseHas('profile_data', ['type' => $section]);

            $this->assertTrue(
                method_exists($profile, $section),
                'Profile does not have method '.$section
            );
                
            foreach ($component->lastRenderedView->data as $data) {
                $this->assertContains($data->id, $profile->$section->pluck('id'));
            };
        
            $this->assertArrayHasKey($section, $component->paginators);
            $this->assertEquals(1, $component->paginators[$section]);
            $this->assertEquals(2, $component->page);
        }
    }  

    /**
    *  Test profile section type value
    * 
    *  @return void
    */
    public function testProfileSectionType()
    {
        $profile = Profile::factory()->hasData()->create();

        $section = 'wrong_data_type_value';

        $invalid_section = ProfileSectionType::tryFrom($section);

        $this->assertNull($invalid_section, 'Invalid section');

        $component = Livewire::test(ProfileDataCard::class, ['profile' => $profile, 'editable' => true, 'data_type' => 'news'])
                                    ->assertHasNoErrors()
                                    ->assertViewIs("livewire.profile-data-cards.news");
        
        $component
                ->set('data_type', $section)
                ->assertHasErrors('data_type')
                ->assertDontSee('data')
                ->assertEmitted('alert', 'Invalid section.', 'danger')
                ->assertDontSeeHtml("<section id=\"$section\" class=\"card\">", '<h3>', '<div class="entry">');
    }

}