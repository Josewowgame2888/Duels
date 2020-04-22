<?php

declare(strict_types=1);

namespace duels\task;

use duels\Duels;
use pocketmine\scheduler\Task;
use pocketmine\Player;

class SongTask extends Task
{
    private $ticks = 0;
    private $player;

    public function __construct(Player $p)
    {
        $this->player = $p;
    }

    public function onRun($currentTick)
    {
            $p = $this->player;
            $tick = $this->ticks;
            $this->ticks++;
            if($tick === 1)
            {
                $p->sendNoteSound(17);
                $p->sendNoteSound(1);
            }
            if($tick === 2)
            {
                $p->sendNoteSound(23);
            }
            if($tick === 3)
            {
                $p->sendNoteSound(23);
                $p->sendNoteSound(1);
            }
            if($tick === 4)
            {
                $p->sendNoteSound(23);
                $p->sendNoteSound(1);
            }
            if($tick === 5)
            {
                $p->sendNoteSound(22);
                $p->sendNoteSound(3);
            }
            if($tick === 6)
            {
                $p->sendNoteSound(20);
                $p->sendNoteSound(5);
            }
            if($tick === 7)
            {
                $p->sendNoteSound(18);
                $p->sendNoteSound(6);
            }
            if($tick === 8)
            {
                $p->sendNoteSound(10);
            }
            if($tick === 9)
            {
                $p->sendNoteSound(1);
            }
            if($tick === 10)
            {
                $p->sendNoteSound(10);
            }
            if($tick === 11)
            {
                $p->sendNoteSound(6);
                $p->sendNoteSound(6);
            }
            if($tick === 12)
            {
                Duels::getMain()->getServer()->getScheduler()->cancelTask($this->getTaskId());
            }
        
    }
}