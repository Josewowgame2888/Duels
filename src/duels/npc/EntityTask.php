<?php
namespace duels\npc;

use duels\Duels;
use pocketmine\scheduler\Task;

class EntityTask extends Task
{

    public function onRun($currentTick)
    {
        if(EntityManager::getMax() === 0 && count(Duels::getMain()->getServer()->getDefaultLevel()->getPlayers()) > 0)
        {
            EntityManager::add();
        }
    }
}