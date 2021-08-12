<?php

class PshDatabase
{
    private static $db_conn;

    private function __construct() { }

    static function conn(): PDO
    {
        if (!self::$db_conn) {
            @ [
                'MYSQL_HOST' => $host,
                'MYSQL_DATABASE' => $dbname,
                'MYSQL_USER' => $user,
                'MYSQL_PASSWORD' => $pass
            ] = getenv();

            if (!$host) {
                throw new Exception("Environment variable missing", 1130);
            }

            self::$db_conn = new PDO(
                "mysql:host=$host;dbname=$dbname", $user, $pass
            );
        }

        return self::$db_conn;
    }
}