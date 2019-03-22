<?php
namespace App;

use Alr\ObjectDotNotation\Data;

interface AgentInterface
{
    public function process(Data $agent);
}