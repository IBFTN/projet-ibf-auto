<?php
class Database
{
    private static ?PDO $db = null;

    public static function getConnection(): PDO
    {
        if (self::$db === null) {
            self::$db = new PDO('mysql:host=127.0.0.1;dbname=ibf_motors;charset=utf8mb4', 'root', '');
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        return self::$db;
    }
}
?>