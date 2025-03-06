<?php

namespace Core\Database;

use PDO;
use PDOException;

class DB {
    private static array $connections = [];

    /**
     * Kết nối đến database cụ thể.
     *
     * @param string|null $dbName Tên database cần kết nối. Nếu null, dùng database mặc định.
     * @return PDO Kết nối PDO đã tạo.
     */
    public static function connect(?string $dbName = null): PDO {
        // Nếu không có tên database, lấy mặc định từ cấu hình
        $dbName = $dbName ?? 'tech_global_27_08_2024';

        // Nếu kết nối đã tồn tại, trả về ngay
        if (isset(self::$connections[$dbName])) {
            return self::$connections[$dbName];
        }
        
        try {
            $dsn = "mysql:host=171.244.133.58;dbname=$dbName;charset=utf8mb4";
            $pdo = new PDO($dsn, "admin", "198700b8883346f70", [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
            self::$connections[$dbName] = $pdo;
            return $pdo;
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
}
