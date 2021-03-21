<?php

namespace Database\Factories;

use App\Profile;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'full_name' => function (array $attributes) {
                return User::find($attributes['user_id'])->display_name;
            },
            'first_name' => function (array $attributes) {
                return User::find($attributes['user_id'])->firstname;
            },
            'last_name' => function (array $attributes) {
                return User::find($attributes['user_id'])->lastname;
            },
            'slug' => function (array $attributes) {
                return User::find($attributes['user_id'])->pea;
            },
            'type' => 0,
            'public' => 1,
        ];
    }
}
