<?php

namespace App\Commands;

use App\AgentHelper;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use LaravelZero\Framework\Commands\Command;

class AgentCreate extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'agent:create
                            {config : The JSON formatted configuration for the agent}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Creates an agent';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config_json = $this->argument('config');

        if (file_exists($config_json)) {
            $config_json = file_get_contents($config_json);
        }

        if (!$this->isValidJson($config_json)) {
            $this->error('JSON data from input is not valid. Must be a file or stdin data.');
            return 1;
        }

        $aname = $this->ask('Agent name');

        $ah = new AgentHelper();

        $atype = $this->choice('Agent type', $ah->getAgentClasses());
        $ahours = $this->ask('How many time should we keep the events?', 0);

        $this->table(
            ['Agent Name', 'Agent type', 'Event keeping time', 'Agent config'],
            [[$aname, $atype, $ahours.'h', $config_json]]
        );

        if($this->confirm('Should we proceed with this agent?')) {
            DB::table('agents')->insert(
                [
                    'name' => $aname,
                    'agent_class' => $atype,
                    'propagate_immediately' => true,
                    'working' => false,
                    'hours_keep_events' => $ahours,
                    'agent_config' => $config_json,
                ]
            );
            $this->info('Agent created successfully');
        }
    }

    public function isValidJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
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
