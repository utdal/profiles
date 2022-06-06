<?php

namespace Database\Factories;

use App\Student;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

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
            'middle_name' => null,
            'last_name' => function (array $attributes) {
                return User::find($attributes['user_id'])->lastname;
            },
            'slug' => function (array $attributes) {
                return User::find($attributes['user_id'])->pea;
            },
            'type' => 'undergraduate',
            'status' => 'drafted',
        ];
    }

    /**
     * Student factory state: drafted
     *
     * @return @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function drafted()
    {
        return $this->withStatus('drafted');
    }

    /**
     * Student factory state: submitted
     *
     * @return @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function submitted()
    {
        return $this->withStatus('submitted');
    }

    /**
     * Student factory state: with a particular status
     *
     * @param string $status
     * @return @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withStatus($status)
    {
        return $this->state(function(array $attributes) use ($status) {
            return [
                'status' => $status,
            ];
        });
    }
}
