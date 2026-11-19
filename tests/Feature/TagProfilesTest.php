<?php

namespace Tests\Feature;

use App\Enums\ProfileType;
use App\Http\Livewire\AcceptingStudentsToggle;
use App\Http\Livewire\TagsModal;
use App\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Spatie\Tags\Tag;
use Tests\Feature\Traits\LoginWithRole;
use Tests\TestCase;

/**
 * @group tags
 */
class TagProfilesTest extends TestCase
{
    use LoginWithRole;
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    protected function profileTag(string $name): Tag
    {
        return Tag::create(['name' => $name, 'type' => Profile::class]);
    }

    protected function taggedProfile(Tag $tag, array $attributes = []): Profile
    {
        $profile = Profile::factory()->hasData()->create($attributes);
        $profile->attachTag($tag);

        return $profile;
    }

    /**
     * Tagged cache keys are namespaced by the full tag set, so this must match TagsController@show.
     */
    protected function isTagPageCached(string $slug): bool
    {
        return Cache::tags(['profile_tags', 'profiles', 'profile_data'])->has("tag-profiles:{$slug}");
    }

    public function testTagPageListsPublicProfilesForAllTagsSharingTheSlug(): void
    {
        $tag = $this->profileTag('Robotics');
        $lowercase_tag = $this->profileTag('robotics');

        $tagged = $this->taggedProfile($tag);
        $tagged_lowercase = $this->taggedProfile($lowercase_tag);
        $private = $this->taggedProfile($tag, ['public' => 0]);
        $unlisted = $this->taggedProfile($tag, ['type' => ProfileType::Unlisted]);
        $untagged = Profile::factory()->hasData()->create();

        $this->get(route('tags.show', ['slug' => 'robotics']))
            ->assertOk()
            ->assertSee($tagged->name)
            ->assertSee($tagged_lowercase->name)
            ->assertDontSee($private->name)
            ->assertDontSee($unlisted->name)
            ->assertDontSee($untagged->name)
            ->assertSee('2 profiles tagged with');
    }

    public function testTagPageResultsAreCached(): void
    {
        $tag = $this->profileTag('Robotics');
        $this->taggedProfile($tag);

        $this->assertFalse($this->isTagPageCached('robotics'));

        $this->get(route('tags.show', ['slug' => 'robotics']))->assertOk();

        $this->assertTrue($this->isTagPageCached('robotics'));

        // A pivot change that bypasses the app's tag endpoints doesn't invalidate the cache...
        $late = $this->taggedProfile($tag);
        $this->get(route('tags.show', ['slug' => 'robotics']))->assertDontSee($late->name);

        // ...until the cache is flushed.
        Cache::tags(['profile_tags'])->flush();
        $this->get(route('tags.show', ['slug' => 'robotics']))->assertSee($late->name);
    }

    /**
     * Which cached results exist for the "Robotics" tag page, tag search and keyword search.
     * Tagged cache keys are namespaced by the full tag set, so these must match the controllers.
     */
    protected function cachedRoboticsResults(): array
    {
        return [
            'tag page' => $this->isTagPageCached('robotics'),
            'tag search' => Cache::tags(['profile_tags', 'profiles', 'profile_data'])->has('search-tag:' . md5('robotics')),
            'keyword search' => Cache::tags(['profiles', 'profile_data'])->has('keyword-ids:without-names:' . md5('Robotics')),
        ];
    }

    /**
     * Visit the "Robotics" tag page and search so all their results are cached.
     */
    protected function cacheRoboticsResults(): void
    {
        $this->get(route('tags.show', ['slug' => 'robotics']))->assertOk();
        $this->get(route('profiles.index', ['search' => 'Robotics']))->assertOk();

        $this->assertSame(['tag page' => true, 'tag search' => true, 'keyword search' => true], $this->cachedRoboticsResults());
    }

    public function testChangesToTagsAndProfilesClearCachedResults(): void
    {
        $tag = $this->profileTag('Robotics');
        $profile = $this->taggedProfile($tag);
        $keyword_profile = $this->profileWithTitle('Professor of Robotics');
        $this->loginAsAdmin();

        // Tag changes clear the tag page and tag search.
        $this->cacheRoboticsResults();
        $this->post(route('tags.api.update'), [
            'model' => Profile::class,
            'id' => $profile->id,
            'tags' => ['Robotics', 'Vision'],
        ]);
        $this->assertFalse($this->cachedRoboticsResults()['tag page'], 'Saving tags in the tag editor');
        $this->assertFalse($this->cachedRoboticsResults()['tag search'], 'Saving tags in the tag editor');

        $this->cacheRoboticsResults();
        Livewire::test(TagsModal::class, ['model' => $keyword_profile])->call('toggleTag', $tag->id);
        $this->assertTrue($keyword_profile->fresh()->tags->contains($tag));
        $this->assertFalse($this->cachedRoboticsResults()['tag page'], 'Toggling a tag in the tags modal');
        $this->assertFalse($this->cachedRoboticsResults()['tag search'], 'Toggling a tag in the tags modal');

        // Profile data changes clear everything.
        $all_cleared = ['tag page' => false, 'tag search' => false, 'keyword search' => false];

        $this->cacheRoboticsResults();
        Livewire::test(AcceptingStudentsToggle::class, ['profile' => $profile])->set('not_accepting_students', true);
        $this->assertSame('1', $profile->information()->first()->not_accepting_students);
        $this->assertSame($all_cleared, $this->cachedRoboticsResults(), 'Using the accepting students toggle');

        $this->cacheRoboticsResults();
        $this->delete(route('profiles.archive', ['profile' => $keyword_profile]))->assertRedirect(route('profiles.table'));
        $this->assertSame($all_cleared, $this->cachedRoboticsResults(), 'Archiving a profile');

        // Deleting the user cascades to the profile in the database, without model events.
        $this->cacheRoboticsResults();
        $this->delete(route('users.delete', ['user' => $profile->user]))->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('profiles', ['id' => $profile->id]);
        $this->assertSame($all_cleared, $this->cachedRoboticsResults(), 'Deleting a user');

        // Renaming or deleting the tag itself.
        $this->cacheRoboticsResults();
        $tag->update(['name' => 'Robots']);
        $this->assertFalse($this->isTagPageCached('robotics'), 'Renaming a tag');

        $this->get(route('tags.show', ['slug' => 'robots']))->assertOk();
        $this->assertTrue($this->isTagPageCached('robots'));
        $tag->delete();
        $this->assertFalse($this->isTagPageCached('robots'), 'Deleting a tag');
    }

    public function testTagLinksPointToTagPage(): void
    {
        $tag = $this->profileTag('Robotics');
        $profile = $this->taggedProfile($tag);
        $tag_url = route('tags.show', ['slug' => 'robotics']);

        $this->get(route('tags.index'))->assertSee($tag_url, false);
        $this->get(route('profiles.show', ['profile' => $profile]))->assertSee($tag_url, false);
    }

    public function testSearchShowsProfilesTaggedWithTheTerm(): void
    {
        $tag = $this->profileTag('Robotics');
        $profile = $this->taggedProfile($tag);
        $untagged = Profile::factory()->hasData()->create();

        $this->get(route('profiles.index', ['search' => 'ROBOTICS']))
            ->assertOk()
            ->assertSee('Profiles (by Tag: ROBOTICS)')
            ->assertSee($profile->name)
            ->assertDontSee($untagged->name)
            ->assertDontSee('No Results.');
    }

    public function testRepeatedSearchUsesCachedTagAndKeywordResults(): void
    {
        $tag = $this->profileTag('Robotics');
        $tagged = $this->taggedProfile($tag);
        $keyword = $this->profileWithTitle('Professor of Robotics');

        $this->get(route('profiles.index', ['search' => 'Robotics']))
            ->assertSee($tagged->name)
            ->assertSee($keyword->name);

        DB::enableQueryLog();

        $this->get(route('profiles.index', ['search' => 'Robotics']))
            ->assertOk()
            ->assertSee($tagged->name)
            ->assertSee($keyword->name);

        // Tag matching joins taggables; keyword matching runs LIKE against profile_data.
        $uncached = collect(DB::getQueryLog())
            ->pluck('query')
            ->filter(fn ($sql) => str_contains($sql, '`taggables`')
                || (str_contains($sql, '`profile_data`') && str_contains($sql, 'like')));

        $this->assertEmpty($uncached, "Repeated search re-ran queries:\n" . $uncached->implode("\n"));
    }

    protected function profileWithTitle(string $title): Profile
    {
        $profile = Profile::factory()->hasData()->create();
        $information = $profile->information->first();
        $information->data = array_merge($information->data, ['title' => $title]);
        $information->save();

        return $profile;
    }

    public function testSearchTermIsEscapedOnlyOnce(): void
    {
        $this->profileWithTitle('Professor of Arts & Humanities');

        $this->get(route('profiles.index', ['search' => 'Arts & Humanities']))
            ->assertOk()
            ->assertSee('Profiles (by Keyword: Arts &amp; Humanities)', false)
            ->assertDontSee('Arts &amp;amp; Humanities', false);
    }

    public function testContainingMatchesEncodedFormOnlyWhenAsked(): void
    {
        $raw = $this->profileWithTitle('Professor of Arts & Humanities');
        $encoded = $this->profileWithTitle('Professor of Arts &amp; Humanities');

        // Default (used by the API): only the raw term, as before.
        $this->assertEquals([$raw->id], Profile::containing('Arts & Humanities')->pluck('id')->all());

        // Opt-in (used by the search page and the tag page fallback): raw and encoded forms.
        $this->assertEqualsCanonicalizing(
            [$raw->id, $encoded->id],
            Profile::containing('Arts & Humanities', null, match_encoded: true)->pluck('id')->all()
        );
    }
}
