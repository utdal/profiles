<?php

namespace Database\Factories;

use App\Profile;
use App\ProfileData;
use App\Helpers\Publication;
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

    protected $existing_publications_data =
    [
        0 => [
            'sort_order' => '2021',
            'title' => 'Existing Publication Title #1',
        ],
        1 => [
            'sort_order' => '2022',
            'title' => 'Existing Publication Title #2',
        ],
        2 => [
            'sort_order' => '2023',
            'title' => 'Existing Publication Title #3',
        ],
    ];

    protected $authors_names_patterns = 
    [
        'return ($this->faker->unique()->lastName() . ", " . strtoupper($this->faker->randomLetter()) . ". " . strtoupper($this->faker->randomLetter()) . ".");',
        'return ($this->faker->unique()->lastName() . ", " . strtoupper($this->faker->randomLetter()). ".");',
        'return ($this->faker->firstName() . " " . $this->faker->lastName());',
        'return ($this->faker->lastName() . " " . strtoupper($this->faker->randomLetter()) . strtoupper($this->faker->randomLetter()));', 
        'return ($this->faker->lastName() . " " . strtoupper($this->faker->randomLetter()));', 
    ];

    public int $authors_count = 5;

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
                    'authors_formatted' => ['APA' => $this->faker->paragraph()],
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

    /**
     * Data Type "publications" with pre-defined sort_order and title
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function existing_publication($type, $profile = null)
    {
        return $this
                ->count(3)
                ->state(['type' => 'publications'])
                ->$type($profile)
                ->sequence(function($sequence) {
                    return [
                        'sort_order' => $this->existing_publications_data[$sequence->index]['sort_order'],
                        'data->title' => $this->existing_publications_data[$sequence->index]['title'],
                    ];
                });
    }

    /**
     * Data Type "publications" sourced from the Orcid API. Formatted, and ready to sync with ProfileData
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function orcid_publication(Profile $profile = null) {
        return $this->state(function (array $attributes) use ($profile) {
            
            $authors = $this->authorsNames();

            return [
                'profile_id' => $profile->id,
                'sort_order' => $this->faker->year(),
                'data' => [
                    'put-code' => $this->faker->numberBetween(99000,100000),
                    'url' => $this->faker->url(),
                    'title' => $this->faker->sentence(),
                    'year' => $this->faker->year(),
                    'type' => 'Journal',
                    'month' => $this->faker->month(),
                    'day' => $this->faker->dayOfMonth(),
                    'journal_title' => $this->faker->sentence(),
                    'doi'  => $this->faker->regexify(config('app.DOI_REGEX')),
                    'eid' => $this->faker->regexify(config('app.EID_REGEX')),
                    'authors' => $authors,
                    'authors_formatted' => [
                        'APA' => Publication::formatAuthorsApa($authors),
                    ],
                    'status' => 'published',
                    'visibility' => true,
                    'citation-type' => $this->faker->optional(0.5)->word(),
                    'citation-value' => $this->faker->optional(0.5)->word(),
                ],
            ];
        });
    }

    /**
     * Return array of authors names formatted in any of the $this->$authors_names_patterns formats
     */
    public function authorsNames() {
        $names = [];

        for ($i = 0; $i < $this->authors_count; $i++) {
            $elem = $this->faker->randomElement(array_keys($this->authors_names_patterns));
            $names[] = eval($this->authors_names_patterns[$elem]);
        }

        return $names;
    }

}
