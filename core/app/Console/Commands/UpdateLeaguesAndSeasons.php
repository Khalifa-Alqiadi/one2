<?php

namespace App\Console\Commands;

use App\Services\UpdatesLeaguesAndSeasonsServices;
use Illuminate\Console\Command;

class UpdateLeaguesAndSeasons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-leagues-and-seasons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Leagues data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $saved = app(UpdatesLeaguesAndSeasonsServices::class)->loadLeagues();
        $this->info("Leagues updated: {$saved}");

        return self::SUCCESS;
    }
}
