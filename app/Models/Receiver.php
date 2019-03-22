<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receiver extends Model
{
    public function senderAgent()
    {
        return $this->belongsTo(Agent::class, 'sender_agent_id');
    }

    public function receiverAgent()
    {
        return $this->belongsTo(Agent::class, 'receiver_agent_id');
    }
}
