<?php

namespace Classes;

class Logger
{
    public function log($message): void
    {
        $log_file = fopen(realpath("logs/logs.log"), "a") or die("Unable to open file!");
        fwrite($log_file, $message . "\n");
        fclose($log_file);
    }
}
