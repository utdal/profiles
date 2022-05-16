<?php

namespace Database\Factories;

use App\Profile;
use App\ProfileData;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProfileData::class;

    /**
     * Building name suffix choices
     *
     * @var array
     */
    protected $building_suffixes = [
        'Hall',
        'Building',
        'Center',
        'Lab',
        'Library',
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'profile_id' => Profile::factory(),
            'type' => 'information',
            'sort_order' => 1,
            'data' => function($attributes) {
                return [
                    'email' => Profile::find($attributes['profile_id'])->user->email,
                    'title' => Profile::find($attributes['profile_id'])->user->title,
                    'phone' => $this->faker->phoneNumber(),
                    'secondary_title' => '',
                    'tertiary_title' => '',
                    'location' => $this->faker->lastName() . ' ' .
                        $this->faker->randomElement($this->building_suffixes) . ' ' .
                        $this->faker->unique()->randomNumber(4),
                    'url' => $this->faker->url(),
                    'url_name' => 'My Website',
                ];
            },
            'public' => 1,
        ];
    }
}
