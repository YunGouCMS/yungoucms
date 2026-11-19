<?php

final class Logger
{
    static private $logFile;
    static private $type = 'admin';

    public function __construct()
    {
        $path = G_APP_PATH."/system/logs/".self::$type;
        if(!file_exists($path)){
            mkdir($path,0755);
        }

        self::$logFile = $path."/".date("y-m-d").".log";
    }

    public static function setType($type){
        self::$type = $type;
    }

    public static function writeLog($message)
    {
        if(!is_string($message)){
            $message = json_encode($message);
        }
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[" . $timestamp . "] " . $message . "\n";
        return file_put_contents(self::$logFile, $logMessage, FILE_APPEND);
    }
}