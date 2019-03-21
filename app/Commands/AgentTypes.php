<?php

namespace App\Commands;

use App\AgentHelper;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Helper\TableSeparator;

class AgentTypes extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'agent:types';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Shows agent available types';

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
        $this->info('This table shows agent types and possible uses.');
        $agents = [];
        $ah = new AgentHelper();
        $agent_classes = $ah->getAgentClasses();
        foreach ($agent_classes as $key => $agent_class) {
            $agent = new $agent_class();
            $agents[] = [$agent->name ?? $agent_class, $agent->description ?? null];
            end($agent_classes);
            if ($key !== key($agent_classes)) {
                $agents[] = new TableSeparator();
            }
        }

        $this->table(['Name', 'Use of the agent'], $agents);
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
