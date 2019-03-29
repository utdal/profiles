<?php

namespace App\Ldap\Scopes;

use Adldap\Laravel\Scopes\ScopeInterface;
use Adldap\Query\Builder;

class UsernameScope implements ScopeInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(Builder $builder)
    {
        $builder->whereHas(config('adldap_auth.usernames.ldap'));
    }
}