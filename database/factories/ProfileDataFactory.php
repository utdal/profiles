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
                    'title' => $this->faker->sentence(),
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
                    'title' => $this->faker->sentence(),
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
                    'title' => $this->faker->sentence(),
                    'url' => $this->faker->url(),
                    'description' => $this->faker->sentence(),
                    'start_date' => $this->faker->year(),
                    'end_date' => $this->faker->year(),
                ],
            ];
        });
    }

    /**
     * Data Type "patents"
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function patents()
    {
        return $this->state(function (array $attributes) {
            $title = $this->faker->sentence();

            return [
                'type' => 'patents',
                'data' => [
                    'title'         => $title,
                    'co_inventors'  => '',
                    'family_prefix' => (string) $this->faker->numberBetween(10000, 99999),
                    'members'       => [
                        'patent_1' => [
                            'patent_internal_id' => $this->faker->numerify('#####US#'),
                            'patent_title'       => $title,
                            'jurisdiction'       => 'US',
                            'patent_no'          => number_format($this->faker->numberBetween(7_000_000, 11_999_999)),
                            'status'             => 'published',
                            'filed_date'         => null,
                            'published_date'     => $this->faker->date('m/d/Y'),
                        ],
                        'patent_2' => [
                            'patent_internal_id' => $this->faker->numerify('#####JP#'),
                            'patent_title'       => $title,
                            'jurisdiction'       => 'JP',
                            'patent_no'          => (string) $this->faker->numberBetween(5_000_000, 6_999_999),
                            'status'             => 'published',
                            'filed_date'         => null,
                            'published_date'     => $this->faker->date('m/d/Y'),
                        ],
                    ],
                ],
            ];
        });
    }
}

