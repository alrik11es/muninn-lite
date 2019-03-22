<?php

namespace App\Commands;

use App\Models\Agent;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class AgentShow extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'agent:show {agent_id}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Show specified agent by id';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {

            $agent = Agent::find($this->argument('agent_id'));



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
