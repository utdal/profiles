<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\UserSetting::class, function (Faker $faker) {
    $departments = [
        'Chemistry',
        'Physics',
        'Biology',
        'Management',
        'Psychology',
        'Economics',
    ];

    return [
        'additional_departments' => $faker->randomElements($departments, 1),
    ];
});
