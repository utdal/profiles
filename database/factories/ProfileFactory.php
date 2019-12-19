<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Profile;
use App\User;
use Faker\Generator as Faker;

$factory->define(Profile::class, function (Faker $faker) {

    return [
        'user_id' => factory(User::class),
        'full_name' => function (array $profile) {
            return User::find($profile['user_id'])->display_name;
        },
        'first_name' => function (array $profile) {
            return User::find($profile['user_id'])->firstname;
        },
        'last_name' => function (array $profile) {
            return User::find($profile['user_id'])->lastname;
        },
        'slug' => function (array $profile) {
            return User::find($profile['user_id'])->pea;
        },
        'type' => 0,
        'public' => 1,
    ];

});
