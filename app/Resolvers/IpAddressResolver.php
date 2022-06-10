<?php

namespace App\Resolvers;

use Illuminate\Support\Facades\Request;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\Resolver;

class IpAddressResolver implements Resolver
{
    /**
     * {@inheritdoc}
     */
    public static function resolve(Auditable $auditable) : string
    {
        return Request::header('HTTP_X_FORWARDED_FOR', Request::ip());
    }
}
