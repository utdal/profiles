<?php

namespace App\Ldap\Scopes;

use Adldap\Laravel\Scopes\ScopeInterface;
use Adldap\Query\Builder;

class UserIdScope implements ScopeInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(Builder $builder)
    {
        $builder->whereHas($builder->getSchema()->userId());
    }
}