<?php

/**
 * OAR-specific LDAP Attribute and property syncing settings.
 *
 * Note: This is not part of the adldap2/adldap2-laravel package.
 * It is specific to this application.
 */

return [

    /**
     * Mapping of attributes to sync from LDAP to the User record.
     *
     * Format: ['user model attribute' => 'ldap attribute']
     */
    'attributes' => [
        'name'          => 'uid',
        'display_name'  => 'cn',
        'college'       => 'college',
        'department'    => 'dept',
        'firstname'     => 'givenname',
        'lastname'      => 'sn',
        'email'         => 'mail',
        'title'         => 'title',
        'pea'           => 'pea',
    ],

    /**
     * Mapping of roles to sync from LDAP to the User record.
     *
     * Format: ['ldap attribute' => 'user model attribute']
     */
    'roles' => [
        'staff'     => 'staff',
        'employee'  => 'staff',
        'student'   => 'student',
        'faculty'   => 'faculty',
        'directory' => 'directory'
    ],

    /**
     * The LDAP attribute that contains the User's roles.
     *
     * This attribute should return an array when accessed via an adldap User instance.
     */
    'role_attribute' => env('ADLDAP_ROLE_ATTRIBUTE', 'edupersonaffiliation'),

   /**
     * Default email domain for accounts.
     *
     */
    'email_domain' => env('DEFAULT_EMAIL_DOMAIN', '@example.com'),

];