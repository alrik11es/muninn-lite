<?php
namespace App;

use Alr\ObjectDotNotation\Data;
use Illuminate\Console\Command;

interface AgentInterface
{
    /**
     * @param Command $output
     * @param Data $agent
     * @return mixed
     */
    public function process($output, Data $agent);
}