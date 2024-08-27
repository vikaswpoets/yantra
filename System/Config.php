<?php

namespace System;

class Config
{
    private static $settings = [];

    public static function load($file): void
    {
        self::$settings = require $file;
    }

    public static function get($key)
    {
        return self::$settings[$key] ?? null;
    }
}
