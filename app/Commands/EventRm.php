<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class EventRm extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'event:rm';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Removes all events';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        array_map('unlink', glob( base_path('muninn-data/events/*.json')));
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
