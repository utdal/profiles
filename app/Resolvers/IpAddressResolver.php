<?php

namespace App\Resolvers;

use Illuminate\Support\Facades\Request;
use OwenIt\Auditing\Contracts\IpAddressResolver as IpAddressResolverContract;

class IpAddressResolver implements IpAddressResolverContract
{
    /**
     * {@inheritdoc}
     */
    public static function resolve() : string
    {
        return Request::header('HTTP_X_FORWARDED_FOR', Request::ip());
    }
}
