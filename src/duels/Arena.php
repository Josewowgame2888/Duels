<?php
namespace duels;

use duels\utils\ZipIntegration;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\entity\Entity;
use pocketmine\network\protocol\AddEntityPacket;

class Arena 
{

    public function init(): void
    {
        if(!file_exists(Duels::getMain()->getDataFolder().'Data'))
        {
            @mkdir(Duels::getMain()->getDataFolder().'Data');
        }

        if(!file_exists(Duels::getMain()->getDataFolder().'Backups'))
        {
            @mkdir(Duels::getMain()->getDataFolder().'Backups');
        }

        $this->loadAll();
    }

    public function getExists(string $name): bool
    {
        if(file_exists(Duels::getMain()->getDataFolder().'Data/'.$name.'.conf'))
        {
            return true;
        }
        return false;
    }

    public function getMaxArenas(): int

    {
        $dir = Duels::getMain()->getDataFolder().'Data/';
        $a = 0;
        if (is_dir($dir) && $gd = opendir($dir)) 
        {
            while (($archivo = readdir($gd)) !== false) 
            {
                $a++;
            }
            closedir($gd);
        }
        return $a - 2;
    }

    public function load(string $name): void
    {
        if($this->getMaxArenas() > 0)
        {
           $this->reload($name);
            $level = Duels::getMain()->getServer()->getLevelByName(Duels::getConfigGame()->getLevel($name));
            Duels::getMain()->getServer()->loadLevel(Duels::getConfigGame()->getLevel($name));
        if(Duels::getConfigGame()->getStatus($name) !== 'conf')
        {
            Duels::getConfigGame()->setStatus($name,'on');
        }
        $level = Duels::getMain()->getServer()->getLevelByName(Duels::getConfigGame()->getLevel($name));
        $level->setTime(0);
        $level->stopTime();
    }
    }

    public function loadAll(): void
    {
        if($this->getMaxArenas() > 0)
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
                    $this->load($name);
                }
            }
        }
    }

    public function reload(string $name): void
    {
        if($this->getMaxArenas() > 0)
        {
            if(Duels::getMain()->getServer()->isLevelLoaded(Duels::getConfigGame()->getLevel($name)))
            {
                Duels::getMain()->getServer()->unloadLevel(Duels::getMain()->getServer()->getLevelByName(Duels::getConfigGame()->getLevel($name)),true);
            }
            ZipIntegration::unzip(Duels::getMain()->getDataFolder().'Backups/',Duels::getMain()->getServer()->getDataPath().'worlds/',Duels::getConfigGame()->getLevel($name));
        }
    }

   

    public function getPlaying(): int
    {
        $max = 0;
        if($this->getMaxArenas() > 0)
        {
            if (empty(Duels::getMain()->getDataFolder().'Data/')) 
            {
                return 0;
            }  
            $scan = scandir(Duels::getMain()->getDataFolder().'Data/');
            foreach ($scan as $files) 
            {
                if ($files !== '..' && $files !== '.') 
                {
                    $name = str_replace('.conf', '', $files);
                    if(Duels::getMain()->getServer()->isLevelLoaded(Duels::getConfigGame()->getLevel($name)))
                    {
                    foreach(Duels::getMain()->getServer()->getLevelByName(Duels::getConfigGame()->getLevel($name))->getPlayers() as $player)
                    {
                    $gamemode = $player->getGamemode();
                      if($gamemode === 3 || $gamemode === 1 || $gamemode === 2 || $gamemode === 0)
                      {
                          $max++;
                      }
                    }
                }
                }
            }
        }
        return $max;
    }

    public function getPlayersinArena(string $name): int
    {
        $max = 0;
        if(Duels::getMain()->getServer()->isLevelLoaded(Duels::getConfigGame()->getLevel($name)))
        {
        foreach(Duels::getMain()->getServer()->getLevelByName(Duels::getConfigGame()->getLevel($name))->getPlayers() as $player)
        {
            $gamemode = $player->getGamemode();
           if($gamemode === 3 || $gamemode === 1 || $gamemode === 2 || $gamemode === 0 && Session::exists($player) && Session::getArenaPlayer($player) === $name)
           {
               $max++;
           }
        }
    }
        return $max;
    }

    public function getALivePlayersinArena(string $name): int
    {
        $max = 0;
        if(Duels::getMain()->getServer()->isLevelLoaded(Duels::getConfigGame()->getLevel($name)))
        {
        foreach(Duels::getMain()->getServer()->getLevelByName(Duels::getConfigGame()->getLevel($name))->getPlayers() as $player)
        {
            $gamemode = $player->getGamemode();
            if($gamemode === 2 || $gamemode === 0 && Session::exists($player) && Session::getArenaPlayer($player) === $name)
            {
                $max++;
            }   
        }
    }
        return $max;
    }

    public function joinRandom(Player $player): void
    {
        if($this->getMaxArenas() > 0)
        {
       $games = array();
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
              if(Duels::getConfigGame()->getStatus($name) === 'on' && $this->getPlayersinArena($name) < 2 && Duels::getMain()->getServer()->isLevelLoaded(Duels::getConfigGame()->getLevel($name)))
              {
                  $games[$name] = $this->getPlayersinArena($name);
              }
           }
        }

        if(count($games) === 0)
        {
            $player->sendMessage('§cCould not locate any available duel.');
        } else {
            $index = array_flip($games);
            $need = $index[max($games)];
            $this->join($player,$need);
        }
    } else {
        $player->sendMessage('§cCould not locate any available duel.'); 
    }
    }

    public function join(Player $player, string $name): void
    {
        Session::_init($player);
        Session::setArenaPlayer($player,$name);
           if($this->getALivePlayersinArena($name) === 0)
           {
               Session::setSlot($player,1);
           } else if($this->getALivePlayersinArena($name) >= 1){
               foreach(Duels::getMain()->getServer()->getLevelByName(Duels::getConfigGame()->getLevel($name))->getPlayers() as $pl)
               {
                   if(Session::getSlot($pl) === 1)
                   {
                       Session::setSlot($player,2);
                   } else if(Session::getSlot($pl) === 2){
                       Session::setSlot($player,1);
                   }
                   
               }
           }
        $player->setGamemode(2);
        $player->getInventory()->clearAll();
        $player->setHealth(20);
        $player->setFoodEnabled(false);
        $player->getInventory()->setItem(8,Item::get(Item::REDSTONE)->setCustomName('§l§eQuit Match'));
        $player->getInventory()->setItem(0,Item::get(Item::BOOK)->setCustomName('§l§eGeneral'));
        $player->teleport(Duels::getMain()->getServer()->getLevelByName(Duels::getConfigGame()->getLevel($name))->getSafeSpawn());
        $player->teleport(Duels::getConfigGame()->getLobby($name));
        $player->sendMessage('§6You have connected to §b'.$name.'/section-'.(mt_rand(10,2000) + 300 * 23));
    }

    public function quit(Player $player): void
    {
        Session::delete($player);
        $player->setMaxHealth(20);
        $player->setHealth(20);
        $player->setGamemode(2);
        $player->getInventory()->clearAll();
        $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_NO_AI, false);
        $player->teleport(Duels::getMain()->getServer()->getDefaultLevel()->getSafeSpawn());
    }

    public function specte(Player $player): void
    {
        $player->setNameTag($player->getName());
        $player->setGamemode(3);
        $player->getInventory()->clearAll();
        $player->setMaxHealth(20);
        $player->setHealth(20);
        self::addStrike($player);
    }

    public function getWinner(string $name): string
    {
        $players = [];
        foreach(Duels::getMain()->getServer()->getLevelByName(Duels::getConfigGame()->getLevel($name))->getPlayers() as $player)
        {
            if(!isset($players[$player->getName()]) && Session::exists($player))
            {
                $players[$player->getName()] = $player->getGamemode();
            }
        }
        asort($players);
        return key($players);
    }

    public function getTime(int $int): string 
    {
        $m = floor($int / 60);
        $s = floor($int % 60);
        return (($m < 10 ? "0" : "") . $m . ":" . ($s < 10 ? "0" : "") . $s);
    }

    public function addStrike(Player $player): void
    {
        $pk = new AddEntityPacket();
        $pk->type = 93;
        $pk->eid = Entity::$entityCount++;
        $pk->metadata = [];
        $pk->yaw = $player->yaw;
        $pk->pitch = $player->pitch;
        $pk->x = $player->x;
        $pk->y = $player->y;
        $pk->z = $player->z;
        foreach(Duels::getMain()->getServer()->getLevelByName($player->getLevel()->getFolderName())->getPlayers() as $p)
        {
            $p->dataPacket($pk);
        }
    }

   
}