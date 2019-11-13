<?php
namespace duels\events;

use pocketmine\event\Listener;
use pocketmine\event\entity\{EntityDamageByEntityEvent, EntityDamageEvent};
use duels\Duels;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\level\particle\HeartParticle;
use pocketmine\level\particle\LargeExplodeParticle;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\math\Vector3;

class DamageEvent implements Listener
{
    public function __construct()
    {
        Duels::getMain()->getServer()->getPluginManager()->registerEvents($this,Duels::getMain());
    }

    public function onHit(EntityDamageEvent $event): void
    {
        $player = $event->getEntity();
        $asassin = false;
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
                    if($event->getFinalDamage() >= $player->getHealth())
                    {
                        $event->setCancelled(true);
                        $asassin = true;
                    }
                    if($asassin === true && $event instanceof EntityDamageByEntityEvent)
                    {
                        Duels::getArena()->specte($player);
                        Duels::getMain()->getServer()->getLevelByName(Duels::getConfigGame()->getLevel($name))->addSound(new AnvilFallSound(new Vector3($player->x,$player->y,$player->z)));
                        Duels::getMain()->getServer()->getLevelByName(Duels::getConfigGame()->getLevel($name))->addParticle(new LargeExplodeParticle(new Vector3($player->x,$player->y-1,$player->z)));
                        Duels::getMain()->getServer()->getLevelByName(Duels::getConfigGame()->getLevel($name))->addParticle(new HeartParticle(new Vector3($player->x,$player->y+1.5,$player->z)));
                    }
                }
            }
        }
    }

   public function onItems(PlayerInteractEvent $event): void
   {
       $player = $event->getPlayer();
       $item = $event->getItem();
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
                   if($item->getId() === Item::MUSHROOM_STEW && $player->getHealth() < 20)
                   {
                       $slot = $item->getCustomName();
                       $player->getInventory()->setItem((int) $slot, Item::get(Item::AIR,0,0));
                       $player->setHealth($player->getHealth()+3.5);
                   }
                   if($item->getId() === Item::DIAMOND_SWORD && $player->getHealth() < 40)
                   {
                       $max = $item->getCustomName();
                       $new = $max-1;
                       $player->getInventory()->setItem(1,Item::get(Item::GOLDEN_APPLE,0, $new)->setCustomName($new));
                       $player->setHealth($player->getHealth()+5.1);
                   }
               }
            }
        }
   }
}