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
    protected $description = 'Process specified agent';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $agent = Agent::find($this->argument('agent_id'));
        if ($agent) {
            $this->info('Processing agent: '.$agent->name.' ('.$agent->class.')');
            /** @var \App\Agents\Agent $ag */
            $ag = new $agent->agent_class();
            $ag->process($agent);
            $this->info('DONE');
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
