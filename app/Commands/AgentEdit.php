<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class AgentEdit extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'agent:edit
                            {agent_id}
                            {--name : Edit the agent name}
                            {--type : Change the agent type}
                            {--hours : How many hours should we keep the events?}
                            {--config : Change your agent JSON config}
                            {--receives : Add agent ids from where this agents would receive events }
                            {--sends : Add agent ids to where this agents would send events }
                            ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Edit an agent based on id';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
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
