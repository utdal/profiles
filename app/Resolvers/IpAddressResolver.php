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
     * @return string
     */
    public static function resolve(Auditable $auditable)
    {
        $ip = Request::header('HTTP_X_FORWARDED_FOR', Request::ip());

        return is_string($ip) ? $ip : '';
    }
}
