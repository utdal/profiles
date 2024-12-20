<?php

namespace App\Repositories\Contracts;

use App\Profile;
use GuzzleHttp\Client;
use \Illuminate\Support\Collection;

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
     * @return string|false
     */
    public function getPersonId(string $faculty_id = null) :string|false;

    /**
     * Get the publications from the API to return a collection of ProfileData
     *  @return Collection<array-key, mixed>|false
     */
    public function getPublications() : Collection|false|null;

    /**
     * Cache publications for the current profile
     * @return Collection
     */
    public function getCachedPublications() : Collection;

    /**
     * Sync a collection of ProfileData publications
     * @return bool
     */
    public function syncPublications() : bool;

    /**
     * Get additional publications codes/identifiers from the API
     * @return array
     */
    public function getPublicationsCodes(string $url) : array;

    /**
     * Get a publication external/additional references
     * @return array
     */
    public function getPublicationReferences(array $record) : array;

    /** 
     * Return the publication contributors names in an array
     * @param array $record
     * @return array
     */ 
    public function getPublicationAuthors(array $record, string $default_author_name) : array;

    /**
     * Make a get request to the API, returns false if the response returns an error
     * @param string $url
     * @return array|false
     */
    public function sendRequest(string $url) : array|false;

    /**
     * Return the service provider http client
     */
    public function getHttpClient() : Client;

}