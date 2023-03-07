<?php

namespace Database\Factories;

use App\Profile;
use App\ProfileData;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

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
                    'academic_analytics_id' => $this->faker->optional()->randomNumber(4),
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

    /**
     * Data Type "presentations"/"publications"/"projects"/"additionals"
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function general()
    {
        return $this->state(function (array $attributes) {
            return [
                'data' => [
                    'url' => $this->faker->url(),
                    'title' => $this->faker->sentence(),
                    'year' => $this->faker->year(),
                    'doi' => $this->faker->optional()->regexify(config('app.doi_regex')),
                ],
            ];
        });
    }

    /**
     * Data Type "awards"
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function awards()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'awards',
                'data' => [
                    'name' => $this->faker->catchPhrase(),
                    'organization' => $this->faker->company(),
                    'year' => $this->faker->year(),
                    'category' => Arr::random(['Research', 'Teaching', 'Service', 'Additional']),
                ],
            ];
        });
    }

    /**
     * Data Type "appointments"
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function appointments()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'appointments',
                'data' => [
                    'appointment' => $this->faker->jobTitle(),
                    'organization' => $this->faker->company(),
                    'description' => $this->faker->sentence(),
                    'start_date' => $this->faker->year(),
                    'end_date' => $this->faker->year(),
                ],
            ];
        });
    }

    /**
     * Data Type "affiliations"
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function affiliations()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'affiliations',
                'data' => [
                    'tittle' => $this->faker->sentence(),
                    'description' => $this->faker->sentence(),
                    'start_date' => $this->faker->year(),
                    'end_date' => $this->faker->year(),
                ],
            ];
        });
    }

    /**
     * Data Type "support"
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function support()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'support',
                'data' => [
                    'tittle' => $this->faker->sentence(),
                    'sponsor' => $this->faker->company(),
                    'amount' => $this->faker->randomNumber(5, true),
                    'description' => $this->faker->sentence(),
                    'start_date' => $this->faker->year(),
                    'end_date' => $this->faker->year(),
                ],
            ];
        });
    }

    /**
     * Data Type "news"
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function news()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'news',
                'data' => [
                    'tittle' => $this->faker->sentence(),
                    'url' => $this->faker->url(),
                    'description' => $this->faker->sentence(),
                    'start_date' => $this->faker->year(),
                    'end_date' => $this->faker->year(),
                ],
            ];
        });
    }
}
