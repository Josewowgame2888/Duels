<?php

declare(strict_types=1);

namespace duels\utils;

use pocketmine\Player;
use pocketmine\item\Item;

class GameMode 
{
    public const GAME_MODE_CLASSIC = 0;
    public const GAME_MODE_POTIONS = 1;
    public const GAME_MODE_SOUP = 2;


    private static function getBuildUhc(Player $player): void
    {
        $player->getInventory()->clearAll();
        $player->getInventory()->setHelmet(Item::get(Item::DIAMOND_HELMET));
        $player->getInventory()->setChestplate(Item::get(Item::DIAMOND_CHESTPLATE));
        $player->getInventory()->setLeggings(Item::get(Item::DIAMOND_LEGGINGS));
        $player->getInventory()->setBoots(Item::get(Item::DIAMOND_BOOTS));
        $player->getInventory()->setItem(0,Item::get(Item::DIAMOND_SWORD,0,1));
        $player->getInventory()->setItem(1,Item::get(Item::GOLDEN_APPLE,0,10)->setCustomName('10'));
        $player->getInventory()->setItem(2,Item::get(Item::BOW,0,1));
        $player->getInventory()->setItem(9,Item::get(Item::ARROW,0,64));
        $player->getInventory()->setItem(3,Item::get(Item::FISHING_ROD,0,64));
        $player->setMaxHealth(40);
        $player->setHealth(40);
        $player->setFood(20);
        $player->setGamemode(0);
    }

    private static function getPotion(Player $player): void
    {
        $player->getInventory()->clearAll();
        $player->getInventory()->setHelmet(Item::get(Item::IRON_HELMET));
        $player->getInventory()->setChestplate(Item::get(Item::IRON_CHESTPLATE));
        $player->getInventory()->setLeggings(Item::get(Item::IRON_LEGGINGS));
        $player->getInventory()->setBoots(Item::get(Item::IRON_BOOTS));
        $player->getInventory()->setItem(0, Item::get(Item::IRON_SWORD,0,1));
        $player->getInventory()->addItem(Item::get(Item::SPLASH_POTION,22,35));
        $player->setGamemode(0);
    }

    private static function getSoup(Player $player): void
    {
        $player->getInventory()->clearAll();
        $player->getInventory()->setHelmet(Item::get(Item::IRON_HELMET));
        $player->getInventory()->setChestplate(Item::get(Item::IRON_CHESTPLATE));
        $player->getInventory()->setLeggings(Item::get(Item::IRON_LEGGINGS));
        $player->getInventory()->setBoots(Item::get(Item::IRON_BOOTS));
        $player->getInventory()->setItem(0, Item::get(Item::IRON_SWORD,0,1));
        $player->getInventory()->setItem(1, Item::get(Item::MUSHROOM_STEW,0,1)->setCustomName('1'));
        $player->getInventory()->setItem(2, Item::get(Item::MUSHROOM_STEW,0,1)->setCustomName('2'));
        $player->getInventory()->setItem(3, Item::get(Item::MUSHROOM_STEW,0,1)->setCustomName('3'));
        $player->getInventory()->setItem(4, Item::get(Item::MUSHROOM_STEW,0,1)->setCustomName('4'));
        $player->getInventory()->setItem(5, Item::get(Item::MUSHROOM_STEW,0,1)->setCustomName('5'));
        $player->getInventory()->setItem(6, Item::get(Item::MUSHROOM_STEW,0,1)->setCustomName('6'));
        $player->getInventory()->setItem(7, Item::get(Item::MUSHROOM_STEW,0,1)->setCustomName('7'));
        $player->getInventory()->setItem(8, Item::get(Item::MUSHROOM_STEW,0,1)->setCustomName('8'));
        $player->setGamemode(0);
    }

    public static function giveKit(Player $player, int $mode): void
    {
        switch($mode)
        {
            case self::GAME_MODE_CLASSIC:
                self::getBuildUhc($player);
            break;
            case self::GAME_MODE_POTIONS:
                self::getPotion($player);
            break;
            case self::GAME_MODE_SOUP:
                self::getSoup($player);
            break;
        }
    }

    public static function getText(int $mode): string
    {
        switch($mode)
        {
            case self::GAME_MODE_CLASSIC:
                return 'Classic Mode';
            break;
            case self::GAME_MODE_POTIONS:
                return 'Potion Mode';
            break;
            case self::GAME_MODE_SOUP:
               return 'Soup Mode';
            break;   
        }
    }


}