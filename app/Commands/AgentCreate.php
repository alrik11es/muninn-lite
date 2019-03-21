<?php

namespace App\Commands;

use App\AgentHelper;
use App\Models\Agent;
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
            $file_contents = file_get_contents($config_json);
        } else {
            $this->error('A JSON file is needed as config for agent.');
            return 1;
        }

        if (!$this->isValidJson($file_contents)) {
            $this->error('JSON data from input is not valid. Must be a file or stdin data.');
            return 1;
        }

        $aname = $this->ask('Agent name');

        $ah = new AgentHelper();

        $atype = $this->choice('Agent type', $ah->getAgentClasses());
        $ahours = $this->ask('How many time should we keep the events?', 0);

        $options = [
            "never",
            "every_2m",
            "every_5m",
            "every_10m",
            "every_30m",
            "every_1h",
            "every_2h",
            "every_5h",
            "every_12h",
            "every_1d",
            "every_2d",
            "every_7d",
            "midnight",
            "1am",
            "2am",
            "3am",
            "4am",
            "5am",
            "6am",
            "7am",
            "8am",
            "9am",
            "10am",
            "11am",
            "noon",
            "1pm",
            "2pm",
            "3pm",
            "4pm",
            "5pm",
            "6pm",
            "7pm",
            "8pm",
            "9pm",
            "10pm",
            "11pm",
        ];

        $aschedule = $this->choice('At what time do you want to schedule the agent?', $options, 0);

        $this->table(
            ['Agent Name', 'Agent type', 'Event keeping time', "Schedule", 'Agent config'],
            [[$aname, $atype, $ahours.'h', $aschedule, $config_json]]
        );

        if($this->confirm('Should we proceed with this agent?', true)) {
            Agent::create( [
                'name' => $aname,
                'agent_class' => $atype,
                'propagate_immediately' => true,
                'working' => false,
                'hours_keep_events' => $ahours,
                'schedule' => $aschedule,
                'config_location' => $config_json,
            ]);
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
