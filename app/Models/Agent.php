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
}
