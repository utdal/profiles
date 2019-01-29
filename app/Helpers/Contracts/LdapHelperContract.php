<?php

namespace App\Helpers\Contracts;

use Adldap\Models\User as LdapUser;

interface LdapHelperContract {

    public function search($displayname, array $fields);

    public function getUser($name);

}