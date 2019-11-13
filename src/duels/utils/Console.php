<?php
namespace duels\utils;
class Console {

        public static function info($message){
        self::send('INFO',$message,'§f');
        }

        public static function error($message){
        self::send('ERROR',$message,'§c');
        }

        public static function warning($message){
        self::send('WARNING',$message,'§e');
        }

    private static function getDate(): string{
        $date = date("m.d.y");
        $final = str_replace(".",":",$date);
        return '['.$final.']';
    }

    private static function send(string $prefix, string $message, $color){
        $br = "\n";
        if(!strlen($message) == 0){
        echo BinarySeralize::_encodeTerminalString('§d'.self::getDate().' §6[Duels /'.$prefix.']:'.$color.' '.$message.'§r'.$br);
        }
    }



}