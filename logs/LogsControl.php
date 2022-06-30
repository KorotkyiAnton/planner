<?php

namespace Logs;

class LogsControl
{
    /**
     * @return void
     * Register when method start
     */
    public static function startProcess(): void
    {
        date_default_timezone_set("Europe/Kiev");
        $time = date("H:i:s");
        file_put_contents(__DIR__."/../temp/log.txt", "[$time] (START) ".$_GET["page"]." activated\n", FILE_APPEND);
    }

    /**
     * @return void
     * Register when method finish successfully
     */
    public static function endProcessSuccessfully(): void
    {
        date_default_timezone_set("Europe/Kiev");
        $time = date("H:i:s");
        file_put_contents(__DIR__."/../temp/log.txt", "[$time] (END) ".$_GET["page"]." successfully finished\n", FILE_APPEND);
    }

    /**
     * @param string $error
     * @return void
     * Register when method finish with an error
     */
    public static function endProcessError(string $error): void
    {
        date_default_timezone_set("Europe/Kiev");
        $time = date("H:i:s");
        file_put_contents(__DIR__."/../temp/log.txt", "[$time] (!WARNING!)".$_GET["page"]." $error\n", FILE_APPEND);
    }
}