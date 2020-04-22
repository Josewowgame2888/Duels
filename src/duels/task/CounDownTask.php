<?php

declare(strict_types=1);

namespace duels\task;

use duels\Duels;
use duels\Session;
use duels\utils\BinarySeralize;
use duels\utils\BlockDespawn;
use pocketmine\scheduler\Task;
use pocketmine\entity\Entity;

use function str_repeat;

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
                    $player->getInventory()->clearAll();
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
            if($this->couldDown === 5)
            {
                $block = new BlockDespawn(Duels::getConfigGame()->getPosLobby($this->arena,2),Duels::getConfigGame()->getPosLobby($this->arena,1),$level);
                $block->remove();
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
            $level->setTime(76000);
            $level->stopTime();
            foreach($level->getPlayers() as $player)
            {
                Duels::getMain()->getServer()->getScheduler()->scheduleRepeatingTask(new SongTask($player),5);
                         $nulo = " ";
                         $player->setNameTag($player->getName());
                        $player->sendMessage('§a============================');
                        if($player->getGamemode() === 0 || $player->getGamemode() === 2)
                        {
                           $player->sendMessage(str_repeat($nulo,15).'§eWin §7- §b+2XP');
                        } else {
                           $player->sendMessage(str_repeat($nulo,11).'§eParticipation §7- §b+1XP');
                        }
                           $player->sendMessage(str_repeat($nulo,9).'§eTime §7[§d'.'0'.'§7] - §b+1XP');
                        $player->sendMessage('§a============================');
                Duels::getArena()->quit($player);
            }
            Duels::getMain()->getServer()->getScheduler()->scheduleRepeatingTask(new ResetMapTask($this->arena),10);
            Duels::getMain()->getServer()->getScheduler()->cancelTask($this->getTaskId());
        }
    }
}