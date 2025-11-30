<?php

namespace App\Infra\Database;

class Connection
{
    private static ?\PDO $pdo = null;

    public static function get(): \PDO
    {
        if (self::$pdo === null) {
            $dbPath = __DIR__ . '/../../../database/parking.db';
            @mkdir(dirname($dbPath), 0777, true);
            self::$pdo = new \PDO("sqlite:$dbPath");
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::createTable();
        }
        return self::$pdo;
    }

    private static function createTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS records (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            plate TEXT NOT NULL,
            type TEXT NOT NULL,
            entry_time TEXT NOT NULL,
            exit_time TEXT,
            amount REAL
        )";
        self::$pdo->exec($sql);
    }
}