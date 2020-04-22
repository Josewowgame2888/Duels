<?php

declare(strict_types=1);

namespace duels\npc;

use duels\Duels;
use pocketmine\event\Listener;
use pocketmine\event\entity\{EntityDamageEvent,EntityDamageByEntityEvent};
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\network\protocol\MovePlayerPacket;
use pocketmine\Player;
use pocketmine\math\Vector2;

use function atan2;

class EntityEvent implements Listener
{
    public function __construct()
    {
        Duels::getMain()->getServer()->getPluginManager()->registerEvents($this, Duels::getMain());
    }

    public function onHit(EntityDamageEvent $event): void
    {
        $entity = $event->getEntity();
        if($entity instanceof DuelEntity && $event instanceof EntityDamageByEntityEvent)
        {
            $event->setCancelled(true);
            $event->getDamager()->sendMessage('§7Looking for an available duel...');
            Duels::getArena()->joinRandom($event->getDamager());
        }
    }

    public function onRotation(PlayerMoveEvent $event): void
    {
        $player = $event->getPlayer();
		$from = $event->getFrom();
		$to = $event->getTo();

		if($from->distance($to) < 0.1) {
			return;
        }
        foreach ($player->getLevel()->getNearbyEntities($player->getBoundingBox()->expand(40, 40, 40), $player) as $e)
        {
            if($e instanceof Player )
            {
                continue;
            }
            $xdiff = $player->x - $e->x;
			$zdiff = $player->z - $e->z;
			$angle = atan2($zdiff, $xdiff);
			$yaw = (($angle * 180) / M_PI) - 90;
			$ydiff = $player->y - $e->y;
			$v = new Vector2($e->x, $e->z);
			$dist = $v->distance($player->x, $player->z);
			$angle = atan2($dist, $ydiff);
			$pitch = (($angle * 180) / M_PI) - 90;
            if($e->getSaveId() === 'DuelEntity')
            {
                $pk = new MovePlayerPacket();
                $pk->eid = $e->getId();
                $pk->yaw = $yaw;
                $pk->bodyYaw = $yaw;
                $pk->pitch = $pitch;
                $pk->onGround = $e->onGround;
                $pk->x = $e->x;
                $pk->y = $e->y+$e->getEyeHeight();
                $pk->z = $e->z;
                $player->dataPacket($pk);
            }
        }
    }
}