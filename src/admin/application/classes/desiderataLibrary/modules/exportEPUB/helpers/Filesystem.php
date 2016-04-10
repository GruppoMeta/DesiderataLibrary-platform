<?php

class desiderataLibrary_modules_exportEPUB_helpers_Filesystem
{

    public static function mkdir($folders)
    {
        $folders = is_array($folders) ? $folders : array($folders);
        foreach ($folders as $folder) {
            if (is_dir($folder)) continue;
            mkdir($folder);
        }
    }

    public static function rmdir($folder)
    {
        if (is_dir($folder)) {
            $files = scandir($folder);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") self::rmdir("$folder/$file");
            }
            @rmdir($folder);
        } else {
            @unlink($folder);
        }
    }

    public static function copy($src, $dst)
    {
        if (is_dir($src)) {
            @mkdir($dst);
            $files = scandir($src);
            foreach ($files as $file)
                if ($file != "." && $file != "..") self::copy("$src/$file", "$dst/$file");
        } else if (file_exists($src)) {
            copy($src, $dst);
        }
    }
}
