<?php

namespace Tests\Feature\Traits;

use App\User;
use Adldap\Laravel\Facades\Adldap;
use Adldap\Laravel\Facades\Resolver;
use Adldap\Models\User as LdapUser;

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

        return Adldap::getProvider($provider)->make()->user([
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
}