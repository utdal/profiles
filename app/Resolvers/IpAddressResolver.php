<?php

namespace App\Resolvers;

use Illuminate\Support\Facades\Request;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\Resolver;

class IpAddressResolver implements Resolver
{
    /**
     * {@inheritdoc}
     * 
     * @return string|array|null
     */
    public static function resolve(Auditable $auditable)
    {
        return Request::header('HTTP_X_FORWARDED_FOR', Request::ip());
    }
}
