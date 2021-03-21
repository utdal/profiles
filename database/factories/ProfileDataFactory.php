<?php

namespace Database\Factories;

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
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
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
                'email' => $this->faker->safeEmail,
                'phone' => $this->faker->phoneNumber,
                'title' => $this->faker->jobTitle,
                'secondary_title' => '',
                'tertiary_title' => '',
                'location' => $this->faker->lastName . ' ' .
                    $this->faker->randomElement($building_suffixes) . ' ' .
                    $this->faker->unique()->randomNumber(4),
            ],
            'public' => 1,
        ];
    }
}
