<?php
namespace App;

class AgentHelper
{
    public function getAgentClasses()
    {
        $files = scandir(dirname(__FILE__).'/Agents');

        $final = [];
        foreach ($files as $file) {
            if (preg_match('/\.php/', $file)) {
                $file = str_replace('.php', '', $file);
                $final[] = '\\App\\Agents\\'.$file;
            }
        }

        return $final;
    }

    public function listAgentNamesOrClasses()
    {
        $result = [];
        $agent_classes = $this->getAgentClasses();
        foreach ($agent_classes as $key => $agent_class) {
            $agent = new $agent_class();
            $result[$agent_class] = $agent->name ?? $agent_class;
        }
        return $result;
    }
}