<?php

namespace App\Interfaces;

use App\Profile;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

interface PublicationsApiInterface
{
    /**
     * Constructor.
     *
     * @param Profile $profile
     */
    public function __construct(Profile $profile);

    public function sendRequest(string $url): Response;

    public function getHttpClient();

}
