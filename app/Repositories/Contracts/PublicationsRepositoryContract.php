<?php

namespace App\Repositories\Contracts;

use App\Profile;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;

interface PublicationsRepositoryContract
{
    /**
     * Constructor.
     *
     * @param Profile $profile
     */

    public function __construct(Profile $profile);

    /**
     * Receive an attribute to get from the API the identifier necessary to retrieve the publications
     * @param string $faculty_id
     * @return mixed|true
     */
    public function getPersonId(string $faculty_id = null);

    /**
     * Retrieve the publications from the API to return a ProfileData model collection
     *  @param int $faculty_id
     *  @return \Illuminate\Database\Eloquent\Collection|false
     */
    public function getPublications();

    /**
     * Cache publications for the current profile
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCachedPublications();

    public function syncPublications();

    /**
     * Make a get request to the API
     */
    public function sendRequest(string $url): array|false;

    /**
     * Return the service provider http client
     */
    public function getHttpClient(): Client;

}