<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Daemon extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'daemon';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Executes the event listener daemon';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Parallel processing example
        /*for ($i=0; $i<10; $i++) {
            // open ten processes
            for ($j=0; $j<10; $j++) {
                $pipe[$j] = popen('script2.php', 'w');
            }

            // wait for them to finish
            for ($j=0; $j<10; ++$j) {
                pclose($pipe[$j]);
            }
        }*/
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
