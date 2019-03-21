<?php
namespace App;

use App\Models\Event;
use Carbon\Carbon;

class EventHelper
{
    /**
     * @param \App\Models\Agent $agent
     * @param $item
     */
    public function generateEvent(\App\Models\Agent $agent, $item): void
    {
        if ($agent->hours_keep_events > 0) {
            $hke = Carbon::now()->addHours($agent->hours_keep_events);
        } else {
            $hke = null;
        }
        Event::create([
            "payload" => json_encode($item),
            "agent_id" => $agent->id,
            "max_alive_date" => $hke,
        ]);

        $agent->last_event_out = Carbon::now();
    }
}