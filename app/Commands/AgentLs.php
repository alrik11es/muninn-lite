<?php

namespace App\Commands;

use App\Models\Agent;
use App\Models\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\QueryException;
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

    public function __construct()
    {
        parent::__construct();

//        $this->setAliases(['agent:list']);

    }


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
            $this->info('This table shows muninn agents that you\'ve created previously.');
            $headers = [
                'id',
                'name',
                'agent_class',
                'schedule',
                'hours_keep_events',
                'propagate_immediately',
                'working',
                'config_location',
            ];
            $agents = Agent::all($headers)->toArray();

            $agents = array_map(function ($item) {
                $item = (object) $item;
                $item->agent_class = str_replace('\App\Agents\\', '', $item->agent_class);
                $item->hours_keep_events = $item->hours_keep_events . 'h';
                $item->working = $item->working == 1 ? 'yes' : 'no';
                $item->propagate_immediately = $item->propagate_immediately == 1 ? 'yes' : 'no';
                return (array) $item;
            }, $agents);

            $this->table(
                [
                    '#',
                    'Agent Name',
                    'Agent type',
                    'Schedule',
                    'Keeping T.',
                    'F. Propagate',
                    'Works?',
                    'Agent config location',
                ],
                $agents
            );
            $this->warn('Number of events: '.Event::count());
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
