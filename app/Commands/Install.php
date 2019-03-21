<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Install extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create muninn-lite database if not exists';

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
            $this->info('SQLITE Muninn database created.');
            $this->info('Installing muninn.');
            $this->call('migrate', [
                '--force'
            ]);
            $this->info('Finished install, you can now use munnin.');
        } else {
            $this->warn('Muninn database already exists.');
        }

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
