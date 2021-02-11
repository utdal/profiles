<?php

/**
 * OAR-specific LDAP Attribute and property syncing settings.
 *
 * Note: This is not part of the adldap2/adldap2-laravel package.
 * It is specific to this application.
 */

return [

    /**
     * Whether we should sync the user's attributes, roles, and school,
     * locally from LDAP every time they log in.
     */
    'sync_attributes' => env('LDAP_LOGIN_SYNC_ATTRIBUTES', true),
    'sync_roles' => env('LDAP_LOGIN_SYNC_ROLES', true),
    'sync_school' => env('LDAP_LOGIN_SYNC_SCHOOL', true),

    /**
     * Mapping of attributes to sync from LDAP to the User record.
     *
     * Format: ['user model attribute' => 'ldap attribute']
     */
    'attributes' => [
        'name'          => env('LDAP_SCHEMA_USERNAME', 'samaccountname'),
        'display_name'  => env('LDAP_SCHEMA_DISPLAYNAME', 'displayname'),
        'college'       => env('LDAP_SCHEMA_SCHOOL', 'college'),
        'department'    => env('LDAP_SCHEMA_DEPARTMENT', 'department'),
        'firstname'     => env('LDAP_SCHEMA_FIRSTNAME', 'givenname'),
        'lastname'      => env('LDAP_SCHEMA_LASTNAME', 'sn'),
        'email'         => env('LDAP_SCHEMA_EMAIL', 'mail'),
        'pea'           => env('LDAP_SCHEMA_EMAILNICKNAME', 'mail'),
        'title'         => env('LDAP_SCHEMA_TITLE', 'title'),
    ],

    /**
     * Other LDAP attributes (non-syncing)
     */
    'other_attributes' => [
        'office'        => env('LDAP_SCHEMA_OFFICE', 'physicaldeliveryofficename'),
        'telephone'     => env('LDAP_SCHEMA_TELEPHONE', 'telephonenumber'),
        'primaryrole'   => env('LDAP_SCHEMA_PRIMARY_ROLE'),
    ],

    /**
     * Mapping of roles to sync from LDAP to the User record.
     *
     * Format: ['application role name' => 'ldap group name']
     */
    'roles' => [
        'staff'     => env('LDAP_SCHEMA_ROLE_STAFF', 'staff'),
        'student'   => env('LDAP_SCHEMA_ROLE_STUDENT', 'students'),
        'faculty'   => env('LDAP_SCHEMA_ROLE_FACULTY', 'faculty'),
    ],

    'school_from' => [
        'department' => env('LDAP_INFER_SCHOOL_FROM_DEPT', 'faculty|staff'),
        'college' => env('LDAP_INFER_SCHOOL_FROM_COLLEGE', 'student|studentworker'),
    ],

   /**
     * Default email domain for accounts.
     *
     */
    'email_domain' => env('DEFAULT_EMAIL_DOMAIN', '@example.com'),

];