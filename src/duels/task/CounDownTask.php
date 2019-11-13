<?php
namespace duels\task;

use duels\Duels;
use duels\Session;
use duels\utils\BinarySeralize;
use pocketmine\scheduler\Task;
use pocketmine\item\Item;
use pocketmine\entity\Entity;

class CounDownTask extends Task
{
    private $arena;
    private $couldDown = 6;

    public function __construct(string $name)
    {
        $this->arena = $name;
    }

    public function onRun($currentTick)
    {
        $level = Duels::getMain()->getServer()->getLevelByName(Duels::getConfigGame()->getLevel($this->arena));
        if(Duels::getArena()->getALivePlayersinArena($this->arena) === 2 && Duels::getConfigGame()->getStatus($this->arena) === 'starting')
        {
            if($this->couldDown === 6)
            {
                foreach($level->getPlayers() as $player)
                {
                    if(Session::getSlot($player) === 1 || Session::getSlot($player) === 2)
                    {
                        if(Session::getSlot($player) === 1)
                        {
                            $player->teleport(Duels::getConfigGame()->getPos($this->arena,1));
                            $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_NO_AI, true);
                        }
                        if(Session::getSlot($player) === 2)
                        {
                            $player->teleport(Duels::getConfigGame()->getPos($this->arena,2));
                            $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_NO_AI, true);
                        }
                    } else {
                       Duels::getArena()->quit($player);
                       $player->sendMessage('§cAn internal error occurred in the game. Try connecting to another game.'); 
                    }
                    
                } 
            }

            if($this->couldDown > 0)
            {
                $this->couldDown--;
                foreach($level->getPlayers() as $player)
                {
                    $player->setTitle(BinarySeralize::_IntToSimbolString($this->couldDown),'',1);
                    $player->sendNoteSound(1);
                }
            }
            if($this->couldDown === 0)
            {
                foreach($level->getPlayers() as $player)
                {
                    $player->setTitle(BinarySeralize::_IntToSimbolString(0),'',1);
                }
                Duels::getMain()->getServer()->getScheduler()->scheduleRepeatingTask(new MatchTask($this->arena),20);
                Duels::getConfigGame()->setStatus($this->arena,'off');
                Duels::getMain()->getServer()->getScheduler()->cancelTask($this->getTaskId());
            }

        } else {
            foreach($level->getPlayers() as $player)
            {
                $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_NO_AI, false);
                $player->getInventory()->clearAll();
                $player->setHealth(20);
                $player->setFoodEnabled(false);
                $player->getInventory()->setItem(8,Item::get(331)->setCustomName('§l§eQuit Match'));
            }
            Duels::getConfigGame()->setStatus($this->arena,'on');
            Duels::getMain()->getServer()->getScheduler()->cancelTask($this->getTaskId());
        }
    }
}