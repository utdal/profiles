<?php

namespace App\Interfaces;

use App\Profile;
use GuzzleHttp\Client;
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
     * @param string $client_faculty_id
     * @return mixed|true
     */
    public function getPersonId(string $client_faculty_id);

    /**
     * Retrieve the publications from the API to return a ProfileData model collection
     *  @param int $faculty_id
     *  @return \Illuminate\Database\Eloquent\Collection|false
     */
    public function getPublications(int $faculty_id);

    /**
     * Cache publications for the current profile
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCachedPublications(int $profile_id, int $academic_analytics_id);

    /**
     * Make a get request to the API
     */
    public function sendRequest(string $url): array|false;

    /**
     * Return the service provider http client
     */
    public function getHttpClient(): Client;

}
