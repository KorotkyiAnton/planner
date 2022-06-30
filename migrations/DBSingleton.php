<?php

namespace Migrations;

use Logs\LogsControl;
use PDO;

class DBSingleton
{
    private static PDO $db;

    private function __construct()
    {
    }

    /**
     * @return void
     * static method to initiate connection to DB
     */
    public static function initiateDb(): void
    {
        try {
            static::$db = new PDO("mysql:host=127.0.0.1;dbname=korotkyianton", "root","");
        } catch (\PDOException $error) {
            LogsControl::endProcessError($error->getTraceAsString());
        }
    }

    /**
     * @return PDO|void
     * get PDO to share it in other classes
     */
    public static function getDb()
    {
        if (static::$db !== null) {
            return static::$db;
        }
    }
}