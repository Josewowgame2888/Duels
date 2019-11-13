<?php
namespace duels\events;

use duels\Duels;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;

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
                    if($event->getItem()->getId() === 331 && $event->getItem()->getCustomName() === '§l§eQuit Match')
                    {
                        Duels::getArena()->quit($event->getPlayer());
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
               if($event->getPlayer()->getLevel()->getFolderName() === Duels::getConfigGame()->getLevel($name))
               {
                   if($event->getCause() === EntityDamageEvent::CAUSE_SUFFOCATION || $event->getCause() === EntityDamageEvent::CAUSE_FALL)
                   {
                       $event->setCancelled(true);
                   }
               }
            }
        }

    }

    public function deny(Player $player): void
    {
        $player->sendNoteSound(19);
    }
}