<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class AgentRm extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'agent:rm {agent_id}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Removes an agent based on it\'s id';

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
        $num = DB::table('agents')->where('id', $this->argument('agent_id'))->count();
        if ($num > 0) {
            DB::table('agents')->where('id', $this->argument('agent_id'))->delete();
            $this->info('Agent ' . $this->argument('agent_id') . ' deleted');
        } else {
            $this->error('Agent ' . $this->argument('agent_id') . ' does not exist');
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
