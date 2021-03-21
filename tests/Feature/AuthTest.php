<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\MockLdap;
use Tests\TestCase;

/**
 * @group auth
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;
    use MockLdap;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /**
     * Test User creation and authentication from LDAP.
     *
     * @return void
     */
    public function testLdapAuthenticationWorks(): void
    {
        $user = User::factory()->make();

        // The actual values here don't really matter,
        // because we're mocking the LDAP User Resolver
        $credentials = [
            'name' => $user->name,
            'password' => '12345',
        ];

        $this->mockLdapUserResolver($user, $credentials);

        $this->post(route('login'), $credentials)
            ->assertRedirect('/');

        $this->assertInstanceOf(User::class, Auth::user());

        // User should be created in the database when authenticating
        // for the first time with LDAP
        $this->assertDatabaseHas('users', $user->getAttributes());

        // Logged-in User's display_name should show in the navbar
        $this->get('/')->assertSee($user->display_name);
    }

}
