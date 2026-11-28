<?php

class Database {
    private static $pdo = null;

    public static function connect() {
        if (self::$pdo === null) {
            $host = '127.0.0.1';
            $port = '3306';
            $db   = 'bakery_db';
            $user = 'root';
            $pass = '';

            try {
                $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
                self::$pdo = new PDO($dsn, $user, $pass);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
