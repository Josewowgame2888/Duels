<?php

declare(strict_types=1);

namespace duels\utils;

use function date;
use function str_replace;
use function strlen;

class Console {

    public static function info($message): void
    {
        self::send('INFO',$message,'§f');
    }

    public static function error($message): void
    {
        self::send('ERROR',$message,'§c');
    }

    public static function warning($message): void
    {
        self::send('WARNING',$message,'§e');
    }

    private static function getDate(): string
    {
        $date = date("m.d.y");
        $final = str_replace(".",":",$date);
        return '['.$final.']';
    }

    private static function send(string $prefix, string $message, $color): void
    {
        $br = "\n";
        if(!strlen($message) === 0){
        echo BinarySeralize::_encodeTerminalString('§d'.self::getDate().' §6[Duels /'.$prefix.']:'.$color.' '.$message.'§r'.$br);
        }
    }



}