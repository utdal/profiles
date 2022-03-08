<?php

namespace App\Helpers;

use Adldap\AdldapInterface;
use Adldap\Connections\ProviderInterface;
use Adldap\Models\User as LdapUser;
use App\Helpers\Contracts\LdapHelperContract;
use App\Ldap\Handlers\LdapAttributeHandler;
use App\User;

class LdapHelper implements LdapHelperContract
{
    /** @var Adldap instance */
    protected $adldap;

    /** @var App\Ldap\Handlers\LdapAttributeHandler */
    public $handler;

    /** @var ProviderInterface the LDAP provider */
    public $provider;

    /** @var Schema the LDAP server schema */
    public $schema;

    /** @var string the displayname attribute name */
    public $displayname_attribute;

    /** @var string the username attribute name */
    public $username_attribute;

    /**
     * Class constructor.
     */
    public function __construct(AdldapInterface $adldap)
    {
        $this->adldap = $adldap;
        $this->provider = $this->adldap->getDefaultProvider();
        /** @var Schema */
        $this->schema = $this->provider->getSchema();
        $this->handler = app(LdapAttributeHandler::class);
        $this->username_attribute = $this->schema->loginName();
        $this->displayname_attribute = $this->schema->displayName();
    }

    /**
     * Search for users in LDAP.
     * 
     * @param  string $query_string name to search for
     * @param  array  $fields fields to retrieve
     * @return array
     */
    public function search($query_string, array $fields = [], $to_array = false)
    {
        if (empty($query_string)) {
            return [];
        }
        if (empty($fields)) {
            $fields = [
                $this->username_attribute,
                $this->displayname_attribute,
            ];
        }

        $query = $this->provider->search()
            ->where($this->schema->anr(), '=', $query_string)
            ->select($fields);

        $users = $this->sortUsers($query->get(), $query_string);

        if ($to_array) {
            return $this->flattenUserAttributes($users, $fields);
        }

        return $users;
    }

    /**
     * Sorts users by relevance to a query string.
     *
     * Orders by location of the query string in the user's displayname, and then by displayname.
     * 
     * @param  Illuminate\Support\Collection $users
     * @param  string $query_string
     * @return Illuminate\Support\Collection
     */
    protected function sortUsers($users, $query_string)
    {
        return $users->sortBy(function ($user, $key) use ($query_string) {
            $displayname = $user->getFirstAttribute('displayname');
            $query_string_location = strpos(strtolower($displayname), strtolower($query_string));
            return ($query_string_location !== false) ? $query_string_location . $displayname : $displayname;
        }, SORT_STRING)->values();
    }

    /**
     * Flattens the nested array of user attributes into one level.
     * 
     * @param  Illuminate\Support\Collection $users
     * @param  array $fields
     * @return array
     */
    protected function flattenUserAttributes($users, $fields)
    {
        return $users->transform(function($user, $key) use ($fields) { // flatten the user objects
                return array_map(function($attribute) {
                    return $attribute[0];
                }, array_intersect_key($user->getAttributes(), array_flip($fields)));
            })->all();
    }

    /**
     * Look up and return a local user instance by checking LDAP for that user.
     *
     * @param  string $display_name The user's display name
     * @param  string $name         The user's name/uid
     *
     * @return App\User|boolean
     */
    public function getUser($name)
    {
        $ldap_user = $this->provider->search()
            ->where($this->username_attribute, $name)->get()->first();

        if ($ldap_user && $ldap_user->exists) {
            return $this->getUserFromLdapUser($ldap_user);
        }

        return false;
    }

    /**
     * Finds or creates a new local User based on an LDAP user.
     * 
     * @param  Adldap\Models\User $ldap_user
     * @return App\User
     */
    protected function getUserFromLdapUser($ldap_user)
    {
        $user = User::firstOrCreate([
            'name' => $ldap_user->getFirstAttribute($this->username_attribute),
        ]);

        // Sync User attributes and roles
        $this->handler->handle($ldap_user, $user);

        return $user;
    }

}
