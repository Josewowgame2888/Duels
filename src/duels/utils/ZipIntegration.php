<?php
namespace duels\utils;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
class ZipIntegration 
{
    public static function zip(string $path, string $destination, string $name = ''): void
    {
        $rute = realpath($path);
        $zip = new ZipArchive;
        $zip->open($destination.$name.'.zip', $zip::CREATE | $zip::OVERWRITE);
        $flags = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rute),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach($flags as $handle)
        {
            if(!$handle->isDir())
            {
                $realtive = $name . '/' . substr($handle, strlen($path) + 1);
                $zip->addFile($handle, $realtive);
            }
        }
        $zip->close();
        unset($zip, $rute, $flags);
    }

    public static function unzip(string $path, string $destination, string $name = ''): void
    {
        $zip = new ZipArchive;
        $zip->open($path.$name.'.zip');
        $zip->extractTo($destination);
        $zip->close();
    }


    private static function deleteDir($dirPath)
    {
        if (is_dir($dirPath)) {
            $objects = scandir($dirPath);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
                        self::deleteDir($dirPath . DIRECTORY_SEPARATOR . $object);
                    } else {
                        unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dirPath);
        }
    }


}

