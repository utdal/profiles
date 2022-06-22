<?php

namespace Database\Seeders;

use Spatie\Tags\Tag;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Factories\TagFactory;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        TagFactory::new()
            ->create();

        $this->command->line("✅ 1 new has been created.");
    }
}