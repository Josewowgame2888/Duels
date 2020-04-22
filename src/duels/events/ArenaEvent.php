<?php

declare(strict_types=1);

namespace duels\events;

use duels\Duels;
use duels\Session;
use duels\utils\Form;
use pocketmine\event\block\{BlockPlaceEvent,BlockBreakEvent};
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\{PlayerCommandPreprocessEvent, PlayerDropItemEvent,PlayerInteractEvent,PlayerQuitEvent};
use pocketmine\item\Item;
use pocketmine\Player;

use function scandir;
use function str_replace;
use function strlen;
use function strtolower;

class ArenaEvent implements Listener
{
    public function __construct()
    {
        Duels::getMain()->getServer()->getPluginManager()->registerEvents($this, Duels::getMain());
    }

    public function onBreak(BlockBreakEvent $event): void
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
                 if($event->getPlayer()->getLevel()->getFolderName() === Duels::getConfigGame()->getLevel($name))
                 {
                     $event->setCancelled(true); 
                 }
              }
            } 
    }

    public function onPlace(BlockPlaceEvent $event): void
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
               if($event->getPlayer()->getLevel()->getFolderName() === Duels::getConfigGame()->getLevel($name))
               {
                   $event->setCancelled(true);
               }
            }
        }
    }

    public function onInteract(PlayerInteractEvent $event): void
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
               if($event->getPlayer()->getLevel()->getFolderName() === Duels::getConfigGame()->getLevel($name))
               {
                if(Duels::getConfigGame()->getStatus($name) === 'on')
                {
                    if($event->getItem()->getId() === Item::REDSTONE && $event->getItem()->getCustomName() === '§l§eQuit Match')
                    {
                        Duels::getArena()->quit($event->getPlayer());
                    }
                    if($event->getItem()->getId() === Item::BOOK && $event->getItem()->getCustomName() === '§l§eGeneral')
                    {
                        $function = function()
                        {
                        };
                        $version = Duels::getMain()->getDescription()->getVersion();
                        if(strlen($version) <= 1)
                        {
                            $version .= '.0';
                        }
                        $line = [
                            'line1' => '§eClassic Duels is a modality where you will have to fight with an opponent for survival and glory. The modality has three classic pvp modes as they are (Classic Duel, Potions Duel and Soup Duel).',
                            'line2' => '§l§fNews or Updates for §r§a[v'.$version.']',
                            'line3' => '§5-§6 3 Classic game modes.',
                            'line4' => '§5-§6 Instant healing with apples (+5.1) and soups (+3.5).',
                            'line5' => '§5-§6 Life indicator on the player tag.',
                            'line6' => '§dNOTE:§e If you found an error report it to the §9Discord §eserver or §bTwitter. §eOur §c Owner Developer @Josewowgame §eare always aware of the improvement so that everything works normally inside and outside the server.',
                            'br' => "\n",
                            'br2' => "\n"."\n"
                        ];
                        $form = Form::createGUI($function,'§l§cGeneral information',$line['line1'].$line['br2'].$line['line2'].$line['br'].$line['line3'].$line['br'].$line['line4'].$line['br'].$line['line5'].$line['br2'].$line['line6']);
                        $form->sendForm($event->getPlayer());
                    }
                }  
               }
            }
        }
    }

    public function onDrop(PlayerDropItemEvent $event): void
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
               if($event->getPlayer()->getLevel()->getFolderName() === Duels::getConfigGame()->getLevel($name))
               {
                if(Duels::getConfigGame()->getStatus($name) === 'on')
                {
                    $event->setCancelled(true);
                } else {
                    $event->setCancelled(false);
                }
               }
            }
        }   
    }

    public function getDamageDeny(EntityDamageEvent $event): void
    {
        $player = $event->getEntity();
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
               if($player->getLevel()->getFolderName() === Duels::getConfigGame()->getLevel($name))
               {
                   if($event->getCause() === EntityDamageEvent::CAUSE_SUFFOCATION || $event->getCause() === EntityDamageEvent::CAUSE_FALL)
                   {
                       $event->setCancelled(true);
                   }
               }
            }
        }

    }

    public function onQuit(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();
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
               if($player->getLevel()->getFolderName() === Duels::getConfigGame()->getLevel($name))
               {
                   Session::delete($player);
               }
            }
        }

    }

    public function onPlayerCommand(PlayerCommandPreprocessEvent $event): void
    {
        $player = $event->getPlayer();
        $message = strtolower($event->getMessage());
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
               if($player->getLevel()->getFolderName() === Duels::getConfigGame()->getLevel($name) && Duels::getConfigGame()->getStatus($name) !== 'conf')
               {
                $event->setCancelled(true);
                $this->deny($player);
                $player->sendMessage('§7You cannot execute these commands in play.');
               }
            }
        } 

    }

    public function deny(Player $player): void
    {
        $player->sendNoteSound(19);
    }
}
