<?php
namespace App\Agents;


interface Agent
{
    public function process(\App\Models\Agent $agent);
}