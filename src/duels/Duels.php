<?php
namespace duels;

use duels\commands\{PositionCommand,CreatorCommand};
use duels\npc\EntityManager;
use duels\utils\ConfigParse;
use pocketmine\plugin\PluginBase;
use duels\events\{ArenaEvent,DamageEvent};
use duels\task\WaitingTask;

class Duels extends PluginBase
{
    private static $load;
    public static $session = [];

    public function onLoad(): void
    {
        self::$load = $this;
    }

    public function onEnable(): void
    {
        $this->getServer()->getCommandMap()->register('/dpos', new PositionCommand($this));
        $this->getServer()->getCommandMap()->register('/duels', new CreatorCommand($this));
        EntityManager::init();
        self::getArena()->init();
        new ArenaEvent();
        new DamageEvent();
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new WaitingTask(),20);
    }

    public static function getMain(): Duels
    {
        return self::$load;
    }

    public static function getArena(): Arena
    {
        return new Arena();
    }

    public static function getConfigGame(): ConfigParse
    {
        return new ConfigParse();
    }

}