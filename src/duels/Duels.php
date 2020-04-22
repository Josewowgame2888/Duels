<?php

declare(strict_types=1);

namespace duels;

use duels\commands\{PositionCommand,CreatorCommand};
use duels\npc\EntityManager;
use duels\utils\ConfigParse;
use pocketmine\plugin\PluginBase;
use duels\events\{ArenaEvent,DamageEvent};
use duels\task\WaitingTask;
use RuntimeException;

class Duels extends PluginBase
{
    private static $load;
    private static $arena;
    private static $config;
    public static $session = [];

    public function onLoad(): void
    {
        self::$load = $this;
        self::$arena = new Arena();
        self::$config = new ConfigParse();
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
        if(self::$load === null) {
            throw new RuntimeException('Could not load Duels.php');
        }
        return self::$load;
    }

    public static function getArena(): Arena
    {
        if(self::$arena === null) {
            throw new RuntimeException('Could not load Arena.php');
        }
        return self::$arena;
    }

    public static function getConfigGame(): ConfigParse
    {
        if(self::$config === null) {
            throw new RuntimeException('Could not load ConfigParse.php');
        }
        return self::$config;
    }

}