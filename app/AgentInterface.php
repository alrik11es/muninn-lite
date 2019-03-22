<?php
namespace App;


interface AgentInterface
{
    public function process(\App\Models\Agent $agent);
}