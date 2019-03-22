<?php

namespace App\Commands;

use Alr\ObjectDotNotation\Data;
use App\Models\Agent;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class AgentProcess extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'agent:process {agent}';

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
        if (file_exists($this->argument('agent'))) {
            $data = json_decode(file_get_contents($this->argument('agent')));
            $data->filename = $this->argument('agent');
            $d = Data::load($data);

            $agent_type = '\\App\\Agents\\'.$d->get('type');
            $ag = new $agent_type();
            $count = $ag->process($this, $d);

            if ($count) {
               $this->info('Agent processed '.$count.' events');
            }

            $path = base_path('muninn-data/agents-status.json');
            if(file_exists($path)) {
                $r = (array) json_decode(file_get_contents($path));
            } else {
                touch($path);
            }
            $r[$data->filename] = Carbon::now();
            file_put_contents($path, json_encode($r));
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
