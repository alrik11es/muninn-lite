<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class CreateDb extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'createdb';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create muninn-lite database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sqlite = config('database.connections.sqlite');
        if (!file_exists($sqlite['database'])) {
            touch($sqlite['database']);
        }
        $this->info('Database created.');
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
