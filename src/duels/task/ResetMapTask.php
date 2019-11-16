<?php
namespace duels\task;

use duels\Duels;
use pocketmine\scheduler\Task;
use duels\utils\ZipIntegration;
class ResetMapTask extends Task
{

    private $arena;
    private $sleep = 5;
    public function __construct(string $arena)
    {
       $this->arena = $arena; 
    }

    public function onRun($currentTick)
    {
        if($this->sleep > 0 )
        {
            $this->sleep--;
        }
        if($this->sleep === 3)
        {
            if(Duels::getMain()->getServer()->isLevelLoaded(Duels::getConfigGame()->getLevel($this->arena)))
            {
                Duels::getMain()->getServer()->unloadLevel(Duels::getMain()->getServer()->getLevelByName(Duels::getConfigGame()->getLevel($this->arena)),true);
            }
        }

        if($this->sleep === 2)
        {
            ZipIntegration::unzip(Duels::getMain()->getDataFolder().'Backups/',Duels::getMain()->getServer()->getDataPath().'worlds/',Duels::getConfigGame()->getLevel($this->arena));  
        }
        if($this->sleep === 1)
        {
            Duels::getMain()->getServer()->loadLevel(Duels::getConfigGame()->getLevel($this->arena));
            $level = Duels::getMain()->getServer()->getLevelByName(Duels::getConfigGame()->getLevel($this->arena));
            $level->setTime(0);
            $level->stopTime();
        }

        if($this->sleep === 0)
        {
            Duels::getMain()->getServer()->getScheduler()->cancelTask($this->getTaskId());
        }
    }
}