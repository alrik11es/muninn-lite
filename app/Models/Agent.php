<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = [
        'name',
        'agent_class',
        'propagate_immediately',
        'working',
        'hours_keep_events',
        'schedule',
        'config_location',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function senders()
    {
        return $this->hasMany(Receiver::class, 'sender_agent_id');
    }

    public function receivers()
    {
        return $this->hasMany(Receiver::class, 'receiver_agent_id');
    }
}
