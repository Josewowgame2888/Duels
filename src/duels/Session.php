<?php
namespace duels;

use pocketmine\Player;

class Session
{

    public static function _init(Player $player): void
    {
        if(!isset(Duels::$session[$player->getName()]))
        {
            Duels::$session[$player->getName()] = [
                'arena' => null,
                'slot' => null
            ];
        } else {
            unset(Duels::$session[$player->getName()]); 
            Duels::$session[$player->getName()] = [];       
        }
    }

    public static function delete(Player $player): void
    {
        if(isset(Duels::$session[$player->getName()]))
        {
            unset(Duels::$session[$player->getName()]);
        }
    }

    public static function exists(Player $player): bool{
        if(isset(Duels::$session[$player->getName()]))
        {
            return true;
        }
        return false;
    }

    public static function setArenaPlayer(Player $player, string $arena): void
    {
        if(isset(Duels::$session[$player->getName()]))
        {
            Duels::$session[$player->getName()]['arena'] = $arena;
        }
    }

    public static function setSlot(Player $player, int $slot): void
    {
        if(isset(Duels::$session[$player->getName()]))
        {
            Duels::$session[$player->getName()]['slot'] = $slot;
        }  
    }

    public static function getSlot(Player $player): int
    {
        $max = 0;
        if(isset(Duels::$session[$player->getName()]))
        {
            $max = Duels::$session[$player->getName()]['slot'];
        }  
        return $max;
    }

    public static function getArenaPlayer(Player $player): string
    {
        if(isset(Duels::$session[$player->getName()]))
        {
           return  Duels::$session[$player->getName()]['arena'];
        }    
    }

 

}