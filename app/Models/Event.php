<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'payload',
        'agent_id',
        'max_alive_date',
    ];
}
