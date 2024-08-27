<?php

namespace System;

class ErrorHandler
{
    public static function handle($errno, $errstr, $errfile, $errline)
    {
        Logger::log("Error: [{$errno}] {$errstr} - {$errfile}:{$errline}");
        http_response_code(500);
        echo "Something went wrong!";
    }
}

set_error_handler([ErrorHandler::class, 'handle']);
