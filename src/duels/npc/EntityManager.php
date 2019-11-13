<?php
namespace duels\npc;

use duels\Duels;
use duels\utils\BinarySeralize;
use pocketmine\entity\Entity;
use pocketmine\nbt\tag\{Compound,DoubleTag,Enum,FloatTag, StringTag};
class EntityManager
{

    public static function init(): void
    {
        Entity::registerEntity(DuelEntity::class, true);
        Duels::getMain()->getServer()->getScheduler()->scheduleRepeatingTask(new EntityTask(), 30);
        new EntityEvent();
    }

    public static function add(): void
    {
        $nbt = new Compound("", [
			"Pos" => new Enum("Pos", [
				new DoubleTag("", self::getPosition()[0]),
				new DoubleTag("", self::getPosition()[1]),
				new DoubleTag("", self::getPosition()[2])
					]),
			"Motion" => new Enum("Motion", [
				new DoubleTag("", 0),
				new DoubleTag("", 0),
				new DoubleTag("", 0)
					]),
			"Rotation" => new Enum("Rotation", [
				new FloatTag("", self::getPosition()[3]),
				new FloatTag("", self::getPosition()[4])
					]),
        ]);
        $nbt->Skin = new Compound("Skin", array(
            "Data" => new StringTag("Data", BinarySeralize::_getSkinToByte()), 
            "Name" => new StringTag("Name", 'DuelsEntity')));
            foreach(Duels::getMain()->getServer()->getDefaultLevel()->getPlayers() as $player)
            {
                $chunk = $player->chunk;
            }
            $npc = new DuelEntity($chunk,$nbt,true);
            $npc->setNameTag('§bDuels §7[v'.Duels::getMain()->getDescription()->getVersion().']'."\n".'§e0 Players'."\n".'§l§aCLICK TO PLAY');
            $npc->setNameTagVisible(true);
            $npc->spawnToAll();
    }

    public static function getMax(): int
    {
        $i = 0;
        foreach(Duels::getMain()->getServer()->getDefaultLevel()->getEntities() as $entity)
        {
            if($entity instanceof DuelEntity)
            {
                $i++;
            }
        }
        return $i;
    }


    private static function getPosition(): array
    {
        return [230.33,53,13.58,93,0];
    }
}

