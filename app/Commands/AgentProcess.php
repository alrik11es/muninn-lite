<?php

namespace App\Commands;

use App\Models\Agent;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class AgentProcess extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'agent:process {agent_id}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Process specified agent events';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $agent = Agent::find($this->argument('agent_id'));
        if ($agent) {



        } else {
            $this->error('Agent `'.$this->argument('agent_id').'` does not exist');
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
