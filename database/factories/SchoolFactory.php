<?php

namespace Database\Factories;

use App\School;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SchoolFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = School::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = ucwords($this->faker->catchPhrase());

        return [
            'name' => $name,
            'display_name' => "School of $name",
            'short_name' => preg_replace('/\b(\w)|./', '$1', $name),
            'aliases' => implode(';', [
                "$name School",
                Str::slug($name),
            ]),
        ];
    }
}
