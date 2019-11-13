<?php
namespace duels\utils;

use duels\Duels;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\Player;
class ConfigParse
{

    public function create(Player $player, string $name, string $level): void
    {
        $data = new Config(Duels::getMain()->getDataFolder().'Data/'.$name.'.conf',Config::YAML,[
            'level' => $level,
            'pos1' => 0,
            'pos2' => 0,
            'status' => 'conf',
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
}