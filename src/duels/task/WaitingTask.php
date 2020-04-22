<?php

declare(strict_types=1);

namespace duels\task;
use pocketmine\scheduler\Task;
use duels\Duels;

use function scandir;
use function str_replace;

class WaitingTask extends Task
{
    private $tick_devirse = 0;

    public function onRun($currentTick)
    {
        if (empty(Duels::getMain()->getDataFolder().'Data/')) 
        {
         return;
        }  
        $scan = scandir(Duels::getMain()->getDataFolder().'Data/');
        foreach ($scan as $files) 
         {
            if ($files !== '..' && $files !== '.') 
            {
               $name = str_replace('.conf', '', $files);
               $level = Duels::getMain()->getServer()->getLevelByName(Duels::getConfigGame()->getLevel($name));
               if(Duels::getMain()->getServer()->isLevelLoaded(Duels::getConfigGame()->getLevel($name)))
               {
               if($this->tick_devirse < 4){$this->tick_devirse++;}
               if($this->tick_devirse >= 4){$this->tick_devirse = 0;}
               if(Duels::getArena()->getPlayersinArena($name) === 0 && Duels::getConfigGame()->getStatus($name) !== 'on')
               {
                   Duels::getConfigGame()->setStatus($name,'on');
               }
               if(Duels::getArena()->getALivePlayersinArena($name) === 1 && Duels::getConfigGame()->getStatus($name) === 'on')
               {
                 foreach($level->getPlayers() as $p)
                 {
                     switch($this->tick_devirse)
                     {
                         case 0:
                            $p->sendPopup('§6Looking for opponent.');
                         break;
                         case 1:
                            $p->sendPopup('§6Looking for opponent..');
                         break;
                         case 2:
                            $p->sendPopup('§6Looking for opponent...');
                         break;
                         case 3:
                            $p->sendPopup('§6Looking for opponent....');
                         break;
                         case 4:
                            $p->sendPopup('§6Looking for opponent.....');
                         break;

                     }
                 }
               } else if(Duels::getArena()->getALivePlayersinArena($name) === 2 && Duels::getConfigGame()->getStatus($name) === 'on')
               {
                   Duels::getConfigGame()->setStatus($name,'starting');
                   Duels::getMain()->getServer()->getScheduler()->scheduleRepeatingTask(new CounDownTask($name),20);
               }
            }
        }
        }  
    }
}