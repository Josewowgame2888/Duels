<?php
namespace duels\commands;

use pocketmine\command\{PluginCommand,CommandSender};
use duels\Duels;
use duels\utils\Console;
use pocketmine\Player;
class CreatorCommand extends PluginCommand
{
    public function __construct(Duels $main)
    {
        $this->setAliases(['duels','dconf']);
        $this->setDescription('Duels Tools');
    }

    public function getName(): string
    {
        return '/duels';
    }

    public function execute(CommandSender $sender, $commandLabel, array $args)
    {
        if($sender instanceof Player)
        {
            if($sender->isOp())
            {
                if(isset($args[0]))
                {
                    if($args[0] === 'create' || $args[0] === 'pos' || $args[0] === 'save' || $args[0] === 'tp')
                    {
                        switch($args[0])
                        {
                            case 'create'://duels create [name] [level]
                                if(isset($args[1],$args[2]))
                                {
                                    if(!Duels::getArena()->getExists($args[1]))
                                    {
                                        Duels::getConfigGame()->create($sender,$args[1],$args[2]);
                                        $sender->sendMessage('§6The game has been created, use §b/ duels pos [1/2] '.$args[1].' §6to select the spawns');
                                        Console::info('A new game was created: '.$args[1]);
                                    } else {
                                        $sender->sendMessage('§cThe game already exists');
                                    }
                                } else {
                                  $sender->sendMessage('§7use: /duels create [name] [level]');
                                }
                            break;
                            case 'pos'://duels pos [1/2] [name]
                                if(isset($args[1],$args[2]))
                                {
                                    if(Duels::getArena()->getExists($args[2]))
                                    {
                                        Duels::getConfigGame()->setPos($sender,$args[2], (int) $args[1]);
                                        $sender->sendMessage('§6Selected position §7(§b'.$args[1].'§7),§6 be sure to select both after use §b/duels save '.$args[2]);
                                    } else {
                                        $sender->sendMessage('§cThe game not exists');
                                    }
                                } else {
                                    $sender->sendMessage('§7use: /duels pos [1/2] [name]');
                                }
                            break;
                            case 'save'://duels save [name]
                                if(isset($args[1]))
                                {
                                    if(Duels::getArena()->getExists($args[1]))
                                    {
                                            $sender->sendMessage('§l§aThe game has been created correctly.');
                                            Duels::getConfigGame()->setStatus($args[1],'on');
                                            $sender->teleport(Duels::getMain()->getServer()->getDefaultLevel()->getSafeSpawn());
                                    } else {
                                        $sender->sendMessage('§cThe game not exists');
                                    }
                                } else {
                                    $sender->sendMessage('§7use: /duels save [name]');  
                                }
                            break;
                            case 'tp'://duels tp [level]
                                if(isset($args[1]))
                                {
                                    if(file_exists(Duels::getMain()->getServer()->getDataPath().'/worlds/'.$args[1]))
                                    {
                                        if(!Duels::getMain()->getServer()->isLevelLoaded($args[1]))
                                        {
                                            Duels::getMain()->getServer()->loadLevel($args[1]);
                                        }
                                        $sender->teleport(Duels::getMain()->getServer()->getLevelByName($args[1])->getSafeSpawn());
                                        $sender->setGamemode(1);
                                    } else {
                                        $sender->sendMessage('§cLevel not exists');
                                    }
                                } else {
                                    $sender->sendMessage('§7use: /duels tp [level]');   
                                }
                            break;
                        }
                    } else {
                        $sender->sendMessage('§7use: /duels [create/pos/save]');
                    }
                } else {
                    $sender->sendMessage('§7use: /duels [create/pos/save]');
                }
            }
        } else {
            Console::error('Error> The command can no used in Console. CreatorCommand.php at line 23');
        }
    }
}