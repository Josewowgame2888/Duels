<?php
namespace duels\task;

use duels\Duels;
use duels\Session;
use duels\utils\BinarySeralize;
use duels\utils\GameMode;
use pocketmine\entity\Entity;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;
class MatchTask extends Task
{

    private $start = false;
    private $arena;
    private $time = 0;
    private $delayed = 5;
    private $winner = 'Herobrine';

    public function __construct(string $name)
    {
        $this->arena = $name;
    }

    public function onRun($currentTick)
    {
        $level = Duels::getMain()->getServer()->getLevelByName(Duels::getConfigGame()->getLevel($this->arena));
        if(Duels::getArena()->getPlayersinArena($this->arena) > 0 && Duels::getConfigGame()->getStatus($this->arena) === 'off')
        {
            if(Duels::getArena()->getALivePlayersinArena($this->arena) >= 2 && Duels::getConfigGame()->getStatus($this->arena) === 'off')
            {
                if(!$this->start)
                {
                    //initials events
                    $modes = [
                        GameMode::GAME_MODE_BUILDUHC,
                        GameMode::GAME_MODE_POTIONS,
                        GameMode::GAME_MODE_SOUP
                    ];
                    $key = $modes[array_rand($modes)];
                    foreach($level->getPlayers() as $player)
                    {
                        $level->addSound(new BlazeShootSound(new Vector3($player->x,$player->y,$player->z)));
                        GameMode::giveKit($player,$key);
                        $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_NO_AI, false);
                        $nulo = " ";
                        $player->sendMessage('§a===========================');
                        $player->sendMessage(str_repeat($nulo,12).'§cThe duel has begun');
                        $player->sendMessage(str_repeat($nulo,9).'§bMode§7: §l§6'.GameMode::getText($key));
                        $player->sendMessage('§a============================');
                    }
                    $this->start = true;
                } else {
                    $this->time++;
                    foreach($level->getPlayers() as $player)
                    {
                        $player->setNameTag(BinarySeralize::_IntToSimbolStringHealth($player->getHealth(),$player->getMaxHealth()).$player->getName());
                    }

                }
            } else {
                //TODO: winners
                if(Duels::getArena()->getALivePlayersinArena($this->arena) === 1 && Duels::getConfigGame()->getStatus($this->arena) === 'off')
                {
                 //add winners animation 
                 if($this->delayed === 5)
                 {
                     $level->setTime(76000);
                     $level->stopTime();
                     foreach($level->getPlayers() as $player)
                     {
                         Duels::getMain()->getServer()->getScheduler()->scheduleRepeatingTask(new SongTask($player),5);
                        $this->winner = Duels::getArena()->getWinner($this->arena);
                        $player->setTitle('§l§cGame Over','§6'.$this->winner.' §7win the duel',1);
                        
                     }
                 }
                 if($this->delayed > 0){$this->delayed--;}
                 if($this->delayed === 1)
                 {
                     foreach($level->getPlayers() as $player)
                     {
                         $nulo = " ";
                         $player->setNameTag($player->getName());
                        $player->sendMessage('§a============================');
                        if($player->getGamemode() === 0 || $player->getGamemode() === 2)
                        {
                           $player->sendMessage(str_repeat($nulo,15).'§eWin §7- §b+2XP');
                        } else {
                           $player->sendMessage(str_repeat($nulo,11).'§eParticipation §7- §b+1XP');
                        }
                           $player->sendMessage(str_repeat($nulo,9).'§eTime §7[§d'.Duels::getArena()->getTime($this->time).'§7] - §b+1XP');
                        $player->sendMessage('§a============================');
                        $player->setTitle(' ',' ',1);
                     }
                 }
                 if($this->delayed === 0)
                 {
                     //reset todo
                     foreach ($level->getPlayers() as $player) 
                     {
                         Duels::getArena()->quit($player);
                     }
                     Duels::getArena()->load($this->arena);
                     Duels::getMain()->getServer()->getScheduler()->cancelTask($this->getTaskId());
                 }  
                }
            }

        } else {
            Duels::getMain()->getServer()->getScheduler()->cancelTask($this->getTaskId());
        }
    }

}