<?php

namespace Database\Factories;

use App\Helpers\Semester;
use App\StudentData;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudentDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StudentData::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $semesters = $this->faker->randomElements(Semester::currentAndNext(3));
        $semester_slugs = array_map(function ($semester) {
            return Str::slug($semester);
        }, $semesters);

        return [
            'type' => 'research_profile',
            'sort_order' => 1,
            'data' => [
                'full_name' => $this->faker->firstName() . " " . $this->faker->lastName(),
                'intro' => $this->faker->paragraph(),
                'interest' => $this->faker->paragraph(),
                'semesters' => $semesters,
                'availability' => array_map(function ($semester) {
                    return [
                        'hours' => $this->faker->numberBetween(0, 8 * 5 * 180),
                        'hours_weekdays' => $this->faker->numberBetween(0, 8 * 5),
                        'hours_weekends' => $this->faker->numberBetween(0, 8 * 2),
                        'hours_specific' => $this->faker->sentence(),
                    ];
                }, array_combine($semester_slugs, $semesters)),
                // 'schools' => [] // @todo
                // 'faculty' => [] // @todo
                'languages' => $this->faker->randomElements([
                    'ar',
                    'bn',
                    'zh',
                    'en',
                    'hi',
                    'ja',
                    'pt',
                    'es',
                    'ru',
                    'other',
                ], 2),
                'travel' => $this->faker->randomElement(['0', '1']),
                'travel_other' => $this->faker->randomElement(['0', '1']),
                'animals' => $this->faker->randomElement(['0', '1']),
                'credit' => $this->faker->randomElement(['0', '1']),
                'graduation_date' => Semester::date($this->faker->randomElement(Semester::currentAndNext(9)), false)->format('F Y'),
                'other_info' => $this->faker->paragraph(),
            ],
        ];
    }
}
