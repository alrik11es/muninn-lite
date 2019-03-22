<?php
namespace App;

use App\Models\Event;
use Carbon\Carbon;

class EventHelper
{
    /**
     * @param $item
     */
    public function generateEvent($agent, $item): void
    {
        $item->agent = $agent->filename;
        $h = str_replace('h', '', $agent->keep_events);
        $item->expires_on = Carbon::now()->addHours($h);

        $path = base_path('muninn-data/events/'.uniqid().'-'.md5($agent->filename).'.json');
        file_put_contents($path, json_encode($item));

//        if ($agent->hours_keep_events > 0) {
//            $hke = Carbon::now()->addHours($agent->hours_keep_events);
//        } else {
//            $hke = null;
//        }
//        Event::create([
//            "payload" => json_encode($item),
//            "agent_id" => $agent->id,
//            "max_alive_date" => $hke,
//        ]);
//
//        $agent->last_event_out = Carbon::now();
    }
}