<?php

namespace Database\Factories;

use App\UserSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $departments = [
            'Chemistry',
            'Physics',
            'Biology',
            'Management',
            'Psychology',
            'Economics',
        ];

        return [
            'additional_departments' => $this->faker->randomElements($departments, 1),
        ];
    }
}
