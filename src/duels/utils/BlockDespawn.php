<?php
namespace duels\utils;

use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use duels\Duels;
class BlockDespawn 
{
    private $level;
    private $pos1;
    private $pos2;

    public function __construct(Vector3 $pos1, vector3 $pos2, Level $level)
    {
        $this->level = $level;
        $this->pos1 = $pos1;
        $this->pos2 = $pos2;
    }

    public function remove(): void
    {
        $xMin = min($this->pos1->x,$this->pos2->x);
        $xMax = max($this->pos1->x,$this->pos2->x);

        $zMin = min($this->pos1->z,$this->pos2->z);
        $zMax = max($this->pos1->z,$this->pos2->z);

        $yMin = min($this->pos1->y,$this->pos2->y);
        $yMax = max($this->pos1->y,$this->pos2->y);
        
        for ($x = $xMin; $x < $xMax; ++$x) {
            for ($y = $yMin; $y < $yMax; ++$y) {
                for ($z = $zMin; $z < $zMax; ++$z) {
                    $this->sendAir($x,$y,$z);
                }
            }
        }
    }

    private function isAir(int $x, int $y, int $z): bool
    {
        if($this->level->getBlock(new Vector3($x,$y,$z))->getId() === Block::AIR)
        {
            return true;
        }
        return false;
    }

    private function sendAir(int $x, int $y, int $z): void
    {
        if(!$this->isAir($x,$y,$z))
        {
            $this->level->setBlock(new Vector3($x,$y,$z), Block::get(Block::AIR));
            Duels::getMain()->getServer()->getLogger('BlockDespawn in '.$x.' '.$y.' '.$z);
        }
    }
}