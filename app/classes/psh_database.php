<?php

class PshDatabase
{
    private static $db_conn;

    function __construct()
    {
        if (!self::$db_conn) self::conn();
    }

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

    function findUserIdByNick(string $nick): string
    {
        $query = self::$db_conn->prepare("SELECT uuid FROM users WHERE nickname=?");
        $query->execute([$nick]);
        $uuid = $query->fetchColumn();

        return $uuid;
    }

    function createUser(string $nickname, string $thumbnail): string
    {
        $uuid = self::$db_conn->query("SELECT UUID()")->fetchColumn();

        $query = self::$db_conn->prepare("INSERT INTO users(uuid_bin, nickname, thumbnail) VALUES( UUID_TO_BIN( ? ), ?, ? )");
        $query->execute([$uuid, $nickname, $thumbnail]);

        return $uuid;
    }

    function getLastUpdate(): string
    {
        $last_update = self::$db_conn
            ->query("SELECT game_date FROM game_statistics ORDER BY id DESC LIMIT 1")
            ->fetchColumn();

        return $last_update;
    }

    function getTopScores(int $limit): array
    {
        $query = self::$db_conn->query(
            "SELECT nickname, thumbnail, SUM(score) AS score
            FROM game_statistics_view
            GROUP BY uuid_bin
            ORDER BY score DESC
            LIMIT $limit"
        );

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    function createNewStats(string $uuid, int $score, string $date): bool
    {
        $query = self::$db_conn->prepare("INSERT INTO game_statistics(user_uuid_bin, score, game_date)
            VALUES( UUID_TO_BIN( ? ), ?, ? );");

        return $query->execute([$uuid, $score, $date]);
    }
}