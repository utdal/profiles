<?php

namespace App\Ldap\Auth;

use Adldap\Laravel\Auth\Resolver as AdldapResolver;
use Adldap\Models\User;
use Adldap\Connections\ProviderInterface;
use Illuminate\Contracts\Auth\Authenticatable;

class Resolver extends AdldapResolver
{
    /**
     * {@inheritdoc}
     */
    public function authenticate(User $user, array $credentials = [])
    {
        $username = $user->getAttribute($this->getLdapLoginAttribute());

        return $this->provider->auth()->attempt($username, $credentials['password']);
    }

    /**
     * {@inheritdoc}
     */
    public function getLdapLoginAttribute()
    {
        return config('adldap_auth.usernames.ldap_login_attribute', 'dn');
    }
}