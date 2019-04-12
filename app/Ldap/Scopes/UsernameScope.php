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
        $builder->whereHas(config( 'ldap_auth.identifiers.ldap.locate_users_by'));
    }
}