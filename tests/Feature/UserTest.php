<?php

namespace Tests\Feature;

use App\User;
use App\Profile;
use App\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Tests\Feature\Traits\HasJson;
use Tests\Feature\Traits\LoginWithRole;
use Tests\Feature\Traits\MockLdap;
use Tests\TestCase;

/**
 * @group user
 */
class UserTest extends TestCase
{
    use HasJson;
    use LoginWithRole;
    use MockLdap;
    use RefreshDatabase;
    use WithFaker;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /**
     * Tests permissions for creating users.
     *
     * @return void
     */
    public function testUserPermissions()
    {
        $site_admin_role = Role::whereName('site_admin')->firstOrFail();
        $profiles_editor_role = Role::whereName('profiles_editor')->firstOrFail();
        $school_profiles_editor_role = Role::whereName('school_profiles_editor')->firstOrFail();
        $department_profiles_editor_role = Role::whereName('department_profiles_editor')->firstOrFail();

        $other_user = User::factory()->create();
        $user = $this->loginAsUser();

        // normal user
        $this->get(route('users.create'))->assertStatus(403);
        $this->get(route('users.index'))->assertStatus(403);
        $this->get(route('users.show', ['user' => $user]))->assertStatus(200);
        $this->get(route('users.show', ['user' => $other_user]))->assertStatus(403);

        // site_admin
        $user->attachRole($site_admin_role);
        Cache::flush();
        $this->get(route('users.create'))->assertStatus(200);
        $this->get(route('users.index'))->assertStatus(200);
        $this->get(route('users.show', ['user' => $user]))->assertStatus(200);
        $this->get(route('users.show', ['user' => $other_user]))->assertStatus(200);
        $user->detachRoles();

        // profiles_editor
        $user->attachRole($profiles_editor_role);
        Cache::flush();
        $this->get(route('users.create'))->assertStatus(200);
        $this->get(route('users.index'))->assertStatus(200);
        $this->get(route('users.show', ['user' => $user]))->assertStatus(200);
        $this->get(route('users.show', ['user' => $other_user]))->assertStatus(200);
        $user->detachRoles();

        // school_profiles_editor
        $user->attachRole($school_profiles_editor_role);
        Cache::flush();
        $this->get(route('users.create'))->assertStatus(200);
        $this->get(route('users.index'))->assertStatus(200);
        $this->get(route('users.show', ['user' => $user]))->assertStatus(200);
        $this->get(route('users.show', ['user' => $other_user]))->assertStatus(200);
        $user->detachRoles();

        // department_profiles_editor
        $user->attachRole($department_profiles_editor_role);
        Cache::flush();
        $this->get(route('users.create'))->assertStatus(200);
        $this->get(route('users.index'))->assertStatus(200);
        $this->get(route('users.show', ['user' => $user]))->assertStatus(200);
        $this->get(route('users.show', ['user' => $other_user]))->assertStatus(200);
        $user->detachRoles();
    }

    /**
     * Tests creating a user without a profile
     *
     * @return void
     */
    public function testUserCreation()
    {
        $this->loginAsAdmin();

        // mock an Ldap user to find
        $user = User::factory()->make();
        $this->mockLdapUserSearch($user);

        $response = $this->post(route('users.store'), [
            'name' => $user->name,
            'create_profile' => false,
        ]);

        $this->assertDatabaseHas('users', Arr::except($user->getAttributes(), ['guid']));

        $response->assertRedirect(route('users.index'));
    }

    /**
     * Tests creating a user found not in LDAP
     *
     * @return void
     */
    public function testUserNotFound()
    {
        $this->loginAsAdmin();

        // mock an Ldap user not found
        $name = $this->faker->userName;
        $this->mockLdapUserSearch();

        $response = $this->post(route('users.store'), [
            'name' => $name,
            'create_profile' => true,
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseMissing('users', ['name' => $name]);
        $this->assertEquals(1, User::count()); // only the editor user
        $this->assertEquals(0, Profile::count());
    }

    /**
     * Tests creating a user with a profile
     *
     * @return void
     */
    public function testUserCreationWithProfile()
    {
        $this->loginAsAdmin();

        // mock an Ldap user to find
        $user = User::factory()->make();
        $this->mockLdapUserSearch($user);

        $response = $this->followingRedirects()->post(route('users.store'), [
            'name' => $user->name,
            'create_profile' => true,
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertSee('Profile created.');

        $this->assertDatabaseHas('users', Arr::except($user->getAttributes(), ['guid']));

        $this->assertDatabaseHas('profiles', [
            'user_id' => User::where('name', $user->name)->first()->id,
            'full_name' => $user->display_name,
            'first_name' => $user->firstname,
            'last_name' => $user->lastname,
            'slug' => $user->pea,
        ]);
    }

}
