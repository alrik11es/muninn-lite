<?php

namespace App\Commands;

use App\Models\Agent;
use App\Models\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class EventLs extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'event:ls {--page=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Lists latest events';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->info('   __  ___          _             ___ __     
  /  |/  /_ _____  (_)__  ___    / (_) /____ 
 / /|_/ / // / _ \/ / _ \/ _ \  / / / __/ -_)
/_/  /_/\_,_/_//_/_/_//_/_//_/ /_/_/\__/\__/ 
                                             ');

            $query = Event::orderBy('id', 'desc');
            $total = $query->count();
            if($total > 0) {
                $this->info('This table shows last 10 muninn events that your agents created previously.');
                $pages = floor($total / 10);
                $take = 10;
                if ($this->option('page')) {
                    $skip = $take * $this->option('page');
                    $page = $skip / $take;
                } else {
                    $skip = 0;
                    $page = 1;
                }
                $events = $query->take($take)->skip($skip)->get();
                $this->info("Showing page $page / $pages of a total events of $total");

                $table = [];
                foreach ($events as $event) {
                    $this->output->write('<bg=blue>#' . $event->id . '</> ' . $event->agent->name . ' (' . $event->agent->agent_class . ') ');
                    $this->output->writeln('Alive until: ' . $event->max_alive_date . ')');

//                $this->output->write()
//                $this->info($event->agent->name.' ('.$event->agent->agent_class.')');
//                $this->info('Alive until: '.$event->max_alive_date);
                    $this->comment($event->payload);
                    $this->output->writeln('');
                }
            } else {
                $this->info('No events yet');
            }
//            $this->warn('Number of events: xxx');
        } catch (QueryException $e) {
            $this->error('Have you installed muninn? try `$ muninn install`');
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
