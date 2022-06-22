<?php

namespace Database\Factories;

use Spatie\Tags\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class TagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->unique()->word();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'type' => 'App\Profile'
        ];
    }
    
    /**
     * Override the default value of the attribute type with 'App\Student'
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function type_student()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'App\Student',
            ];
        });
    }
}
