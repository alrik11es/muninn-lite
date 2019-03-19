<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class AgentLs extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'agent:ls';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Lists agents';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('   __  ___          _             ___ __     
  /  |/  /_ _____  (_)__  ___    / (_) /____ 
 / /|_/ / // / _ \/ / _ \/ _ \  / / / __/ -_)
/_/  /_/\_,_/_//_/_/_//_/_//_/ /_/_/\__/\__/ 
                                             ');
        $this->info('This table shows muninn agents that you\'ve created previously.');
        $headers = ['id', 'name', 'agent_class', 'hours_keep_events', 'propagate_immediately', 'working'];
        $agents = DB::table('agents')->get($headers)->toArray();

        $agents = array_map(function($item) {
            $item->hours_keep_events = $item->hours_keep_events.'h';
            $item->working = $item->working == 1 ? 'yes' : 'no';
            $item->propagate_immediately = $item->propagate_immediately == 1 ? 'yes' : 'no';
            return (array) $item;
        }, $agents);

        $this->table(
            ['#','Agent Name', 'Agent type', 'Event keeping time', 'Propagate inmmediately?', 'Working?'],
            $agents
        );
        $this->warn('Number of events: xxx');
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
