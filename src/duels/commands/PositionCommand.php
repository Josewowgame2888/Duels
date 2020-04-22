<?php

declare(strict_types=1);

namespace duels\commands;

use pocketmine\command\{PluginCommand,CommandSender};
use duels\Duels;
use duels\utils\Console;
use pocketmine\Player;
class PositionCommand extends PluginCommand
{
    public function __construct(Duels $main)
    {
        $this->setAliases(['dpos','mypos']);
        $this->setDescription('Admin Tools');
    }

    public function getName(): string
    {
        return '/dpos';
    }

    public function execute(CommandSender $sender, $commandLabel, array $args)
    {
      if($sender->isOp() && $sender instanceof Player)
      {
          $sender->sendMessage('[x:'.$sender->x.'] / [y:'.$sender->y.'] / [z:'.$sender->z.'] / [yaw:'.$sender->yaw.']');
      } else if($sender->isOp()){
         Console::error('Error> The command can no used in Console. PositionCommand.php at line 21');
      } 
    }
}