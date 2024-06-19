<?php

namespace Vesna\DataboxIntegrationChallenge;

class Logger
{
    public static function log($message)
    {
        $logFile = __DIR__ . '/../logs/app.log';
        $currentTime = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[$currentTime] $message\n", FILE_APPEND);
    }
}
