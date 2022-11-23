<?php

namespace App\Console\Commands;

use App\Profile;
use Illuminate\Console\Command;

class AddDoiToExistingPublications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profiles:add-doi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Match Academic Analytics publications to existing publications to add DOI';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        return 0;
    }
}
