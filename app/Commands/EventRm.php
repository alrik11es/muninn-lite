<?php

namespace App\Commands;

use App\Models\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class EventRm extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'event:rm {event_id?} {--all}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Removes an event based on it\'s id';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->option('all')) {
            Event::truncate();
            $this->info('All events deleted');
        } elseif ($this->argument('event_id')) {
            $num = DB::table('events')->where('id', $this->argument('event_id'))->count();
            if ($num > 0) {
                DB::table('events')->where('id', $this->argument('event_id'))->delete();
                $this->info('Event ' . $this->argument('event_id') . ' deleted');
            } else {
                $this->error('Event ' . $this->argument('event_id') . ' does not exist');
            }
        } else {
            $this->error('No event id selected');
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
