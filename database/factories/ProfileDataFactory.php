<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProfileData;
use Faker\Generator as Faker;

$factory->define(ProfileData::class, function (Faker $faker) {

    $building_suffixes = [
        'Hall',
        'Building',
        'Center',
        'Lab',
        'Library',
    ];

    return [
        'type' => 'information',
        'sort_order' => 1,
        'data' => [
            'email' => $faker->safeEmail,
            'phone' => $faker->phoneNumber,
            'title' => $faker->jobTitle,
            'secondary_title' => '',
            'tertiary_title' => '',
            'location' => $faker->lastName . ' ' .
                          $faker->randomElement($building_suffixes) . ' ' .
                          $faker->unique()->randomNumber(4),
        ],
        'public' => 1,
    ];

});
