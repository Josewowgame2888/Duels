<?php
namespace duels\npc;

use duels\Duels;
use pocketmine\entity\Human;

class DuelEntity extends Human
{

    public function entityBaseTick($tickDiff = 1)
    {
        $version = Duels::getMain()->getDescription()->getVersion();
        if(strlen($version) <= 1)
        {
            $version .= '.0.0';
        }
        $this->setNameTag('§bClassic Duels §7[v'.$version.']'."\n".'§e'.Duels::getArena()->getPlaying().' Players'."\n".'§l§aCLICK TO PLAY');
    }
}