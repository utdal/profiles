<?php

namespace Tests\Feature\Traits;

use App\User;
use Adldap\AdldapInterface;
use Adldap\Query\Builder;
use Adldap\Query\Collection as AdldapCollection;
use Adldap\Connections\ProviderInterface;
use Adldap\Laravel\Facades\Adldap;
use Adldap\Laravel\Facades\Resolver;
use Adldap\Models\User as LdapUser;
use Mockery;

trait MockLdap
{
    /**
     * Returns a new LDAP user model with the same attributes as the given User.
     *
     * @param User $user
     *
     * @return LdapUser
     */
    protected function makeLdapUser(User $user): LdapUser
    {
        $provider = config('ldap_auth.connection');
        $ldap_sync_attributes = config('ldap_sync.attributes');

        $ldap_user = Adldap::getProvider($provider)->make()->user([
            'objectguid' => $user->guid,
            $ldap_sync_attributes['name'] => [$user->name], // samaccountname
            $ldap_sync_attributes['display_name'] => [$user->display_name], // displayname
            $ldap_sync_attributes['department'] => [$user->department], // department
            $ldap_sync_attributes['firstname'] => [$user->firstname], // givenname
            $ldap_sync_attributes['lastname'] => [$user->lastname], // sn
            $ldap_sync_attributes['email'] => [$user->email], // mail
            $ldap_sync_attributes['pea'] => [$user->email], // mail
            $ldap_sync_attributes['title'] => [$user->title], // title
        ]);

        $ldap_user->exists = true;

        return $ldap_user;
    }

    /**
     * Mocks the LDAP User Resolver.
     *
     * @param User $user User from which to create resolved LdapUser with the same attributes
     * @param array $credentials the login credentials to use
     * @return void
     */
    protected function mockLdapUserResolver(User $user, array $credentials): void
    {
        $ldap_user = $this->makeLdapUser($user);

        Resolver::shouldReceive('byCredentials')->once()->with($credentials)->andReturn($ldap_user)
            ->shouldReceive('getDatabaseIdColumn')->twice()->andReturn('guid')
            ->shouldReceive('getDatabaseUsernameColumn')->once()->andReturn('name')
            ->shouldReceive('getLdapDiscoveryAttribute')->once()->andReturn('samaccountname')
            ->shouldReceive('authenticate')->once()->andReturn(true);
    }

    /**
     * Mocks the LDAP User Search
     *
     * @param User|null $user User from which to find LdapUser with the same attributes, or null to return no results
     * @return void
     */
    protected function mockLdapUserSearch($user = null): void
    {
        $ldap_user = ($user instanceof User) ? $this->makeLdapUser($user) : null;
        $ldap_users = new AdldapCollection([$ldap_user]);

        $schema = app(AdldapInterface::class)->getDefaultProvider()->getSchema();
        $provider = Mockery::mock(ProviderInterface::class);

        $provider
            ->shouldReceive('search')->andReturnSelf()
            ->shouldReceive('select')->andReturnSelf()
            ->shouldReceive('where')->andReturnSelf()
            ->shouldReceive('orWhereContains')->andReturnSelf()
            ->shouldReceive('getSchema')->andReturn($schema)
            ->shouldReceive('get')->andReturn($ldap_users);

        $this->partialMock(AdldapInterface::class, function ($ad_mock) use ($provider) {
            $ad_mock->shouldReceive('getDefaultProvider')->andReturn($provider);
        });
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