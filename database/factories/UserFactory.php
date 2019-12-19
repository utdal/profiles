<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {

    $firstname = $faker->firstName;
    $lastname = $faker->lastName;
    $initials = substr(strtolower($firstname), 0, 1) . 'x' . substr(strtolower($lastname), 0, 1);
    $firstlast = Str::slug($firstname . $lastname);
    $username = $faker->unique()->numerify("{$initials}######");
    $pea = $faker->unique()->numerify("{$firstlast}##");

    $departments = [
        'Accounting',
        'Art',
        'Biology',
        'Chemistry',
        'Computer Science',
        'History',
        'Neuroscience',
        'Political Science',
        'Sociology',
    ];

    $titles = [
        'Assistant Professor',
        'Associate Professor',
        'Lecturer',
        'Professor',
        'Senior Lecturer',
        'Visiting Scholar',
    ];

    return [
        'name' => $username,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'display_name' => "$firstname $lastname",
        'password' => null, // LDAP auth: we're not storing passwords in the DB
        'pea' => $pea,
        'email' => "{$pea}@example.org",
        'guid' => $faker->uuid,
        'department' => $faker->randomElement($departments),
        'title' => $faker->randomElement($titles),
    ];
});
