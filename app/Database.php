<?php
declare(strict_types=1);

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $driver = (string) env('DB_CONNECTION', 'mysql');
        if ($driver === 'sqlite') {
            $path = (string) env('DB_DATABASE', 'database/database.sqlite');
            if (!str_starts_with($path, '/')) {
                $path = BASE_PATH . '/' . ltrim($path, '/');
            }
            $directory = dirname($path);
            if (!is_dir($directory)) {
                mkdir($directory, 0775, true);
            }
            $dsn = 'sqlite:' . $path;
            $username = null;
            $password = null;
        } else {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                env('DB_HOST', 'localhost'),
                env('DB_PORT', '3306'),
                env('DB_DATABASE', 'ai_kapadokya')
            );
            $username = (string) env('DB_USERNAME', 'root');
            $password = (string) env('DB_PASSWORD', '');
        }

        self::$connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        if ($driver === 'sqlite') {
            self::$connection->exec('PRAGMA foreign_keys = ON');
        }

        return self::$connection;
    }
}

