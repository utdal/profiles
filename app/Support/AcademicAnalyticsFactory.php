<?php

namespace App\Support;

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcademicAnalyticsFactory //extends Factory
{
    /**
     * Define the model's default state.
     *
     *@return array
     */
    public function definition()
    {
        $faker = new Faker();

        return [
            "ArticleTitle"=> null,
            "ArticleId"=> $faker->randomNumber(7, true),
            "JournalId"=> $faker->randomNumber(4, true),
            "JournalName"=> $faker->words(),
            "ArticleYear"=> null,
            "DOI"=> null,
            "PersonId"=> null,
            "PersonName"=> null,
            "ClientFacultyId"=> $faker->email(),
            "Authors"=> $faker->name(),
            "Citations"=> $faker->randomNumber(1, true),
            "MatchCreatedDate"=> $faker->date(),
        ];
    }


    public function whatever(array $attributes)
    {
        $faker = new Faker;

        //return $this->state(function () use ($attributes) {
            return [
                "ArticleTitle"=> $attributes['publication_title'],
                "ArticleId"=> $faker->randomNumber(7, true),
                "JournalId"=> $faker->randomNumber(4, true),
                "JournalName"=> $faker->sentence(),
                "ArticleYear"=> $attributes['publication_year'],
                "DOI"=> $attributes['publication_doi'],
                "PersonId"=> $attributes['aa_id'],
                "PersonName"=> $attributes['person_name'],
                "ClientFacultyId"=> $faker->email(),
                "Authors"=> $faker->name(),
                "Citations"=> $faker->randomNumber(1, true),
                "MatchCreatedDate"=> $faker->date(),
            ];
        //});
    }
}

return [
    "ArticleTitle"=> $faker->optional($weight = 0.4, $default = "{$publication->title} {$this->faker->sentence()}")->sentence(),
    "ArticleId"=> $this->faker->randomNumber(7, true),
    "JournalId"=> $this->faker->randomNumber(4, true),
    "JournalName"=> $this->faker->sentence(),
    "ArticleYear"=> $this->faker->optional($weight = 0.3, $default = $publication->year)->year(),
    "DOI"=> $publication->doi ?? $this->faker->regexify('/(10[.][0-9]{4,}[^\s"\/<>]*\/[^\s"<>]+)/'),
    "PersonId"=> $aa_id,
    "PersonName"=> $person_name,
    "ClientFacultyId"=> $this->faker->email(),
    "Authors"=> $this->faker->name(),
    "Citations"=> $this->faker->randomNumber(1, true),
    "MatchCreatedDate"=> $this->faker->date(),
];

$attributes = [
    'publication_title' => $publication->title,
    'publication_year' => $publication->year,
    'publication_doi' => $publication->doi,
    'aa_id' => $aa_id,
    'person_name' => $person_name,
];


$data = new AcademicAnalyticsFactory(function($d) use ($attributes){
    $d->sequence(
        //['ArticleTitle' => $publication->title],
        ['ArticleTitle' => $this->faker->sentence()],
        //['ArticleTitle' => $publication->title.' '.$this->faker->sentence()],
        //['ArticleTitle' => "{$this->faker->catchPhrase()} {$publication->title} {$this->faker->words()}"],
    )
    ->sequence(
        //['ArticleYear' => $publication->year],
        ['ArticleYear' => $this->faker->year()],
    )
    ->whatever($attributes)
    ->make();
});

return $data;
