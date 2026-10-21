<?php

namespace Tests\Feature;

use App\Profile;
use App\ProfileData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\Feature\Traits\LoginWithRole;
use Tests\TestCase;

/**
 * @group read-only
 */
class ReadOnlyModeTest extends TestCase
{
    use LoginWithRole;
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.read_only' => true]);
    }

    /**
     * Test that public pages remain available, with the navigation unchanged.
     *
     * @return void
     */
    public function testPublicPagesRemainAvailable(): void
    {
        $profile = $this->createProfileWithPublications();

        $this->get(route('profiles.home'))
            ->assertOk()
            ->assertSee('name="search"', false)
            ->assertSee(route('login.show'), false);

        $this->get(route('profiles.show', $profile))
            ->assertOk()
            ->assertSee($profile->full_name);

        $this->get(route('profiles.by_id', $profile->id))
            ->assertRedirect(route('profiles.show', $profile));
    }

    /**
     * Test that browsing all profiles remains available, including pagination.
     *
     * @return void
     */
    public function testBrowsingAllProfilesIsAllowed(): void
    {
        $profile = $this->createProfileWithPublications();

        $this->get(route('profiles.index'))
            ->assertOk()
            ->assertSee($profile->full_name);

        $this->get(route('profiles.index', ['page' => 2]))->assertOk();
    }

    /**
     * Test that the maintenance page explains what is disabled.
     *
     * @return void
     */
    public function testMaintenancePageShowsNotice(): void
    {
        $this->get(route('app.maintenance'))
            ->assertOk()
            ->assertSee('Under Maintenance')
            ->assertSee('as are student research applications.')
            ->assertDontSee('@if', false)
            ->assertSee('Search and login are temporarily disabled');
    }

    /**
     * Test that login is disabled.
     *
     * @return void
     */
    public function testLoginIsDisabled(): void
    {
        $this->get(route('login.show'))->assertRedirect(route('app.maintenance'));

        $this->post(route('login'), ['name' => 'someone', 'password' => '12345'])
            ->assertStatus(503);

        $this->assertGuest();
    }

    /**
     * Test that search and browse are disabled.
     *
     * @return void
     */
    public function testSearchIsDisabled(): void
    {
        $this->get(route('profiles.index', ['search' => 'research']))
            ->assertRedirect(route('app.maintenance'));

        $this->get(route('profiles.index', ['search' => ['research']]))
            ->assertRedirect(route('app.maintenance'));

        $this->get(route('profiles.table'))->assertRedirect(route('app.maintenance'));
        $this->get(route('tags.index'))->assertRedirect(route('app.maintenance'));
        $this->get(route('tags.api.search', ['q' => 'research']))->assertRedirect(route('app.maintenance'));
        $this->getJson(route('tags.api.search', ['q' => 'research']))->assertStatus(503);
    }

    /**
     * Test that student research application pages are disabled.
     *
     * @return void
     */
    public function testStudentPagesAreDisabled(): void
    {
        $this->get(route('students.about'))->assertRedirect(route('app.maintenance'));
        $this->get(route('students.create'))->assertRedirect(route('app.maintenance'));
    }

    /**
     * Test that users who were already logged in are logged out, and can't make changes.
     *
     * @return void
     */
    public function testExistingSessionsAreLoggedOut(): void
    {
        $profile = $this->createProfileWithPublications();
        $this->loginAsAdmin();

        $this->post(route('profiles.update', [$profile, 'information']), [])
            ->assertStatus(503);

        $this->assertGuest();
    }

    /**
     * Test that ProfileDataCard pagination still works through Livewire.
     *
     * @return void
     */
    public function testProfileDataCardPaginationIsAllowed(): void
    {
        $profile = $this->createProfileWithPublications();
        $initial_data = $this->profileDataCardInitialData($this->get(route('profiles.show', $profile)), 'publications');

        $this->postLivewireMessage($initial_data, [
            ['type' => 'callMethod', 'payload' => ['id' => 'a1', 'method' => 'gotoPage', 'params' => [2, 'publications']]],
        ])
            ->assertOk()
            ->assertJsonPath('serverMemo.data.paginators.publications', 2);
    }

    /**
     * Test that other Livewire updates on ProfileDataCard are blocked.
     *
     * @return void
     */
    public function testProfileDataCardOtherUpdatesAreBlocked(): void
    {
        $profile = $this->createProfileWithPublications();
        $initial_data = $this->profileDataCardInitialData($this->get(route('profiles.show', $profile)), 'publications');

        $this->postLivewireMessage($initial_data, [
            ['type' => 'syncInput', 'payload' => ['id' => 'a1', 'name' => 'editable', 'value' => true]],
        ])->assertStatus(503);

        $this->postLivewireMessage($initial_data, [
            ['type' => 'callMethod', 'payload' => ['id' => 'a1', 'method' => '$set', 'params' => ['editable', true]]],
        ])->assertStatus(503);

        $this->postLivewireMessage($initial_data, [
            ['type' => 'callMethod', 'payload' => ['id' => 'a1', 'method' => 'gotoPage', 'params' => [2, 'publications']]],
            ['type' => 'fireEvent', 'payload' => ['id' => 'a2', 'event' => 'refresh', 'params' => []]],
        ])->assertStatus(503);
    }

    /**
     * Test that other Livewire components are blocked, including when the URL names an allowed one.
     *
     * @return void
     */
    public function testOtherLivewireComponentsAreBlocked(): void
    {
        $profile = $this->createProfileWithPublications();
        $initial_data = $this->profileDataCardInitialData($this->get(route('profiles.show', $profile)), 'publications');
        $initial_data['fingerprint']['name'] = 'bookmark-button';
        $updates = [['type' => 'callMethod', 'payload' => ['id' => 'a1', 'method' => 'gotoPage', 'params' => [2]]]];

        $this->postLivewireMessage($initial_data, $updates)->assertStatus(503);
        $this->postLivewireMessage($initial_data, $updates, 'profile-data-card')->assertStatus(503);
    }

    /**
     * Test that nothing is restricted when read-only mode is off.
     *
     * @return void
     */
    public function testReadOnlyModeIsOffByDefault(): void
    {
        config(['app.read_only' => false]);

        $this->get(route('login.show'))->assertOk();
        $this->get(route('profiles.index', ['search' => 'research']))->assertOk();
        $this->get(route('app.maintenance'))->assertRedirect(route('profiles.home'));
    }

    /**
     * Create a public profile with enough publications for more than one page.
     */
    protected function createProfileWithPublications(): Profile
    {
        return Profile::factory()
            ->hasData()
            ->has(ProfileData::factory()->count(20)->state(['type' => 'publications'])->general(), 'data')
            ->create();
    }

    /**
     * Get the Livewire initial data for the ProfileDataCard of the given type from a rendered page.
     */
    protected function profileDataCardInitialData(TestResponse $response, string $data_type): array
    {
        $response->assertOk();

        preg_match_all('/wire:initial-data="([^"]+)"/', $response->getContent(), $matches);

        foreach ($matches[1] as $encoded) {
            $initial_data = json_decode(html_entity_decode($encoded, ENT_QUOTES), true);

            if (($initial_data['fingerprint']['name'] ?? null) === 'profile-data-card'
                && ($initial_data['serverMemo']['data']['data_type'] ?? null) === $data_type) {
                return $initial_data;
            }
        }

        $this->fail("No profile-data-card for {$data_type} found on the page.");
    }

    /**
     * Send a Livewire update request, like the browser would.
     */
    protected function postLivewireMessage(array $initial_data, array $updates, ?string $url_name = null): TestResponse
    {
        $url_name ??= $initial_data['fingerprint']['name'];

        return $this->withHeaders(['X-Livewire' => 'true'])
            ->postJson("/livewire/message/{$url_name}", [
                'fingerprint' => $initial_data['fingerprint'],
                'serverMemo' => $initial_data['serverMemo'],
                'updates' => $updates,
            ]);
    }
}
