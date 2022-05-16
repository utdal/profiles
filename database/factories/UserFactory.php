<?php

namespace Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $firstname = $this->faker->firstName();
        $lastname = $this->faker->lastName();
        $initials = substr(strtolower($firstname), 0, 1) . 'x' . substr(strtolower($lastname), 0, 1);
        $firstlast = Str::slug($firstname . $lastname);
        $username = $this->faker->unique()->numerify("{$initials}######");
        $pea = $this->faker->unique()->numerify("{$firstlast}##");

        $departments = [
            'Accounting',
            'Art',
            'Biology',
            'Chemistry',
            'Computer Science',
            'History',
            'Neuroscience',
            'Political Science',
            'Sociology',
        ];

        $titles = [
            'Assistant Professor',
            'Associate Professor',
            'Lecturer',
            'Professor',
            'Senior Lecturer',
            'Visiting Scholar',
        ];

        return [
            'name' => $username,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'display_name' => "$firstname $lastname",
            'password' => null, // LDAP auth: we're not storing passwords in the DB
            'pea' => $pea,
            'email' => "{$pea}@example.org",
            'guid' => $this->faker->uuid(),
            'department' => $this->faker->randomElement($departments),
            'title' => $this->faker->randomElement($titles),
        ];
    }
}
