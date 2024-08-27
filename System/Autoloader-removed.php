<?php

namespace System;

class Autoloader
{
    protected static $prefix = 'System\\';
    protected static $baseDir;

    public static function register($baseDir)
    {
        self::$baseDir = $baseDir;
        spl_autoload_register([self::class, 'autoload']);
    }

    protected static function autoload($class)
    {
        $len = strlen(self::$prefix);
        if (strncmp(self::$prefix, $class, $len) !== 0) {
            return;
        }

        $relative_class = substr($class, $len);
        $file = self::$baseDir . '/' . str_replace('\\', '/', $relative_class) . '.php';

        if (file_exists($file)) {
            require $file;
        }
    }
}

