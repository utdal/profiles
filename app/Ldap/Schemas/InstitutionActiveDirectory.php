<?php

namespace App\Ldap\Schemas;

use \Adldap\Schemas\ActiveDirectory;

/**
 * Class ActiveDirectory.
 *
 * The active directory attribute schema for easy auto completion retrieval.
 */
class InstitutionActiveDirectory extends ActiveDirectory
{
    /**
     * {@inheritdoc}
     */
    public function employeeNumber()
    {
        return 'name';
    }

}
