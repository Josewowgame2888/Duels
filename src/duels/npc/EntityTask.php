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
        foreach(Duels::getMain()->getServer()->getDefaultLevel()->getEntities() as $entity)
        {
            if($entity instanceof DuelEntity)
            {
                $entity->setNameTag('§bDuels §7[v'.Duels::getMain()->getDescription()->getVersion().']'."\n".'§e'.Duels::getArena()->getPlaying().' Players'."\n".'§l§aCLICK TO PLAY');
            }
        }
    }
}