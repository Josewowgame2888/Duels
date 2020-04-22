<?php

declare(strict_types=1);

namespace duels\utils;

use duels\Duels;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\Player;

use function mt_rand;
use function round;

class ConfigParse
{

    public function create(Player $player, string $name, string $level): void
    {
        $data = new Config(Duels::getMain()->getDataFolder().'Data/'.$name.'.conf',Config::YAML,[
            'level' => $level,
            'pos1' => 0,
            'pos2' => 0,
            'lobby' => 0,
            'lobbyPos1' => 0,
            'lobbyPos2' => 0,
            'status' => 'conf',
            'ID' => Duels::getArena()->getMaxArenas() + (12 + mt_rand(90,200))
        ]);
        $data->save();
        if(!Duels::getMain()->getServer()->isLevelLoaded($level))
        {
            Duels::getMain()->getServer()->loadLevel($level);
        }
        $player->teleport(Duels::getMain()->getServer()->getLevelByName($level)->getSafeSpawn());
    }

    public function setPos(Player $player, string $name, int $pos): void
    {
        $data = new Config(Duels::getMain()->getDataFolder().'Data/'.$name.'.conf',Config::YAML);
        $position = [
            round($player->x,2,PHP_ROUND_HALF_UP),
            round($player->y,0,PHP_ROUND_HALF_UP),
            round($player->z,2,PHP_ROUND_HALF_UP)
        ];
        switch($pos)
        {
            case 1:
                $data->set('pos1',$position);
                $data->save();
            break;
            case 2:
                $data->set('pos2',$position);
                $data->save();
            break;
        }
    }

    public function setPosLobby(Player $player, string $name, int $pos): void
    {
        $data = new Config(Duels::getMain()->getDataFolder().'Data/'.$name.'.conf',Config::YAML);
        $position = [
            round($player->x,2,PHP_ROUND_HALF_UP),
            round($player->y,0,PHP_ROUND_HALF_UP),
            round($player->z,2,PHP_ROUND_HALF_UP)
        ];
        switch($pos)
        {
            case 1:
                $data->set('lobbyPos1',$position);
                $data->save();
            break;
            case 2:
                $data->set('lobbyPos2',$position);
                $data->save();
            break;
        }
    }

    public function setLobby(Player $player, string $name): void
    {
        $data = new Config(Duels::getMain()->getDataFolder().'Data/'.$name.'.conf',Config::YAML); 
        $position = [
            round($player->x,2,PHP_ROUND_HALF_UP),
            round($player->y,0,PHP_ROUND_HALF_UP),
            round($player->z,2,PHP_ROUND_HALF_UP)
        ];
        $data->set('lobby',$position);
        $data->save(); 
    }

    public function save(string $name): void
    {
        ZipIntegration::zip(Duels::getMain()->getServer()->getDataPath().'worlds/'.$this->getLevel($name), Duels::getMain()->getDataFolder().'Backups/',$this->getLevel($name));
    }

    public function setStatus(string $name, string $status): void
    {
        $data = new Config(Duels::getMain()->getDataFolder().'Data/'.$name.'.conf',Config::YAML);
        $data->set('status',$status);
        $data->save();   
    }


    public function getLevel(string $name): string
    {
        $data = new Config(Duels::getMain()->getDataFolder().'Data/'.$name.'.conf',Config::YAML);
        return $data->get('level');  
    }

    public function getStatus(string $name): string
    {
        $data = new Config(Duels::getMain()->getDataFolder().'Data/'.$name.'.conf',Config::YAML);
        return $data->get('status');    
    }
    
    public function getPos(string $name, int $position): Vector3
    {
        $data = new Config(Duels::getMain()->getDataFolder().'Data/'.$name.'.conf',Config::YAML);
        switch($position)
        {
            case 1:
                $pos = $data->get('pos1');
                return new Vector3($pos[0],$pos[1]+1,$pos[2]);
            break;
            case 2:
                $pos = $data->get('pos2');
                return new Vector3($pos[0],$pos[1]+1,$pos[2]);
            break;
        }
        return new Vector3(0,0,0);
    }

    public function getPosLobby(string $name, int $position): Vector3
    {
        $data = new Config(Duels::getMain()->getDataFolder().'Data/'.$name.'.conf',Config::YAML);
        switch($position)
        {
            case 1:
                $pos = $data->get('lobbyPos1');
                return new Vector3($pos[0],$pos[1],$pos[2]);
            break;
            case 2:
                $pos = $data->get('lobbyPos2');
                return new Vector3($pos[0],$pos[1],$pos[2]);
            break;
        }
    }

    public function getLobby(string $name): vector3
    {
        $data = new Config(Duels::getMain()->getDataFolder().'Data/'.$name.'.conf',Config::YAML);
        $pos = $data->get('lobby');
        return new Vector3($pos[0],$pos[1],$pos[2]);    
    }

    public function getID(string $name): int
    {
        $data = new Config(Duels::getMain()->getDataFolder().'Data/'.$name.'.conf',Config::YAML);
        return $data->get('ID'); 
    }
}