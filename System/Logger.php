<?php

namespace System;

class Logger
{
    public static function log($message)
    {
        file_put_contents('../logs/app.log', $message . PHP_EOL, FILE_APPEND);
    }
}
