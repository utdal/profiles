<?php

namespace App\Interfaces;

use App\Profile;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Collection;

interface PublicationsApiInterface
{
    /**
     * Constructor.
     *
     * @param Profile $profile
     */

    public function __construct();

    /**
     * Receive an attribute to get from the API the identifier necessary to retrieve the publications
     */
    public function getPersonId(string $client_faculty_id): int|false;

    /**
     * Retrieve the publications from the API to return a ProfileData model collection
     */
    public function getPublications(int $faculty_id): Collection;

    /**
     * Cache publications for the current profile
     */
    public function getCachedPublications(int $profile_id, int $academic_analytics_id): Collection;

    /**
     * Make a get request to the API
     */
    public function sendRequest(string $url): Response;

    /**
     * Return the decoded the given http response
     */
    public function getResponse(Response $response);

    /**
     * Return the service provider http client
     */
    public function getHttpClient(): Client;

}
