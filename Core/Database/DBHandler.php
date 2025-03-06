<?php

namespace Core\Database;

use PDO;

class DBHandler {
    private ?PDO $connection = null;
    private string $table;

    
    /**
     * ✅ Chạy trực tiếp SQL query với tham số và trả về kết quả
     *
     * @param string $sql    Câu lệnh SQL cần thực thi
     * @param array $params  Danh sách tham số bind vào SQL (nếu có)
     * @return mixed         Trả về mảng dữ liệu nếu là SELECT, true/false nếu là INSERT/UPDATE/DELETE
     */
    public function query(string $sql, array $params = []): mixed {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            
            // Nếu là SELECT, trả về dữ liệu
            if (str_starts_with(strtoupper(trim($sql)), "SELECT")) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            // Nếu là INSERT/UPDATE/DELETE, trả về true nếu thành công
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            // ✅ Debug lỗi SQL
            echo '<pre>SQL Error: ' . $e->getMessage() . '</pre>';
            exit();
        }
    }
    
    
    /**
     * Khởi tạo class DBHandler với tên bảng cần thao tác.
     *
     * @param string $table Tên bảng trong database.
     *
     * @example
     * ```php
     * $db = new DBHandler('users');
     * ```
     */
    
    public function __construct(string $table, ?string $database = null) {
        $this->connection = DB::connect($database); // Kết nối database cụ thể
        $this->table = preg_replace('/[^a-zA-Z0-9_]/', '', $table); // Bảo mật tên bảng
    }
    
    
    
    /**
     * Xây dựng điều kiện SQL từ mảng `$conditions`
     *
     * @param array $conditions Mảng điều kiện (VD: ['status' => 'active'])
     * @return array Mảng chứa 'sql' => câu WHERE và 'params' => tham số
     */
    private function buildConditions(array $conditions): array {
        $whereClauses = [];
        $params = [];
        
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $safeKey = preg_replace('/[^a-zA-Z0-9_]/', '', $key);
                
                if (is_array($value) && isset($value[0])) {
                    switch ($value[0]) {
                        case 'BETWEEN':
                            if (isset($value[1]) && isset($value[2])) {
                                $whereClauses[] = "$safeKey BETWEEN :{$safeKey}_1 AND :{$safeKey}_2";
                                $params["{$safeKey}_1"] = $value[1];
                                $params["{$safeKey}_2"] = $value[2];
                            }
                            break;
                            
                        case 'LIKE':
                            if (!empty($value[1])) {
                                $whereClauses[] = "$safeKey LIKE :$safeKey";
                                $params[$safeKey] = "%" . $value[1] . "%";
                            }
                            break;
                            
                        case 'IN':
                            if (!empty($value[1]) && is_array($value[1])) {
                                $placeholders = implode(", ", array_map(fn($i) => ":{$safeKey}_{$i}", array_keys($value[1])));
                                $whereClauses[] = "$safeKey IN ($placeholders)";
                                foreach ($value[1] as $index => $val) {
                                    $params["{$safeKey}_{$index}"] = $val;
                                }
                            }
                            break;
                    }
                } elseif (!empty($value) || is_numeric($value)) {
                    $whereClauses[] = "$safeKey = :$safeKey";
                    $params[$safeKey] = $value;
                }
            }
        }
        
        // ✅ **Ghép điều kiện WHERE nếu có**
        $sql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";
        
        return ['sql' => trim($sql), 'params' => $params];
    }
    
    /**
     * Đếm số lượng bản ghi trong bảng với điều kiện giống như getList()
     *
     * @param array $conditions Điều kiện lọc (VD: ['status' => 'active'])
     * @return int Số lượng bản ghi thỏa mãn điều kiện
     */
    public function countRows(array $conditions = []): int {
        $queryData = $this->buildConditions($conditions);
        $sql = "SELECT COUNT(*) as total FROM {$this->table} " . $queryData["sql"];
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($queryData['params']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'] ?? 0;
    }
    

    /**
     * Lấy danh sách bản ghi theo điều kiện.
     *
     * @param array $conditions Điều kiện lọc dữ liệu (WHERE, LIKE, BETWEEN, IN).
     * @param array $options Các tùy chọn bổ sung (order_by, group_by, limit).
     * @return array Mảng chứa danh sách bản ghi.
     *
     * @example
     * ```php
     * $users = $db->getList(['age' => ['BETWEEN', 18, 30]], ['order_by' => ['name ASC']]);
     * print_r($users);
     * ```
     */
    public function getList(array $conditions = [], array $options = []): array {
        // ✅ **Chọn cột cần lấy dữ liệu**
        $columns = "*";
        if(!empty($options["columns"])){
            $columns = $options["columns"];
        }
        
        $queryData = $this->buildConditions($conditions);
        
        $sql = "SELECT $columns FROM {$this->table} " . $queryData["sql"];
        
        if (!empty($options['group_by'])) {
            $sql .= " GROUP BY " . implode(", ", $options['group_by']);
        }
        
        if (!empty($options['order_by'])) {
            $sql .= " ORDER BY " . implode(", ", $options['order_by']);
        }
        
        if (!empty($options['limit'])) {
            $sql .= " LIMIT " . (int) $options['limit'];
        }
        
        if (!empty($options['offset'])) {
            $sql .= " OFFSET " . (int) $options['offset'];
        }
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($queryData['params']);
        return $stmt->fetchAll();
    }
    
    /**
     * Lấy một bản ghi duy nhất theo điều kiện.
     *
     * @param array $conditions Điều kiện lọc dữ liệu.
     * @param array $options Các tùy chọn bổ sung (order_by).
     * @return array|null Trả về mảng chứa bản ghi hoặc null nếu không tìm thấy.
     *
     * @example
     * ```php
     * $user = $db->getOne(['id' => 1]);
     * print_r($user);
     * ```
     */
    public function getOne(array $conditions = [], array $options = []): ?array {
        
        $options["limit"] = 1;
        $result = $this->getList($conditions, $options);
        return $result[0] ?? null;
    }

    /**
     * Chèn một dòng dữ liệu vào bảng.
     *
     * @param array $data Dữ liệu cần chèn (cột => giá trị).
     * @return bool Trả về true nếu thành công, false nếu thất bại.
     *
     * @example
     * ```php
     * $db->insert(['name' => 'John Doe', 'email' => 'john@example.com']);
     * ```
     */
    public function insert(array $data): bool {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($data);
    }
    
    /**
     * Chèn nhiều dòng dữ liệu vào bảng cùng lúc.
     *
     * @param array $data Mảng chứa nhiều dòng dữ liệu.
     * @return bool Trả về true nếu thành công, false nếu thất bại.
     *
     * @example
     * ```php
     * $db->multiInsert([
     *     ['name' => 'John Doe', 'email' => 'john@example.com'],
     *     ['name' => 'Jane Smith', 'email' => 'jane@example.com']
     * ]);
     * ```
     */
     public function multiInsert(array $data): bool {
        if (empty($data)) return false;
        
        $columns = implode(", ", array_keys($data[0]));
        $placeholders = "(" . implode(", ", array_fill(0, count($data[0]), "?")) . ")";
        
        $sql = "INSERT INTO {$this->table} ($columns) VALUES " . implode(", ", array_fill(0, count($data), $placeholders));
        $stmt = $this->connection->prepare($sql);
        
        $flattenedData = [];
        foreach ($data as $row) {
            $flattenedData = array_merge($flattenedData, array_values($row));
        }
        
        return $stmt->execute($flattenedData);
     }
    

    /**
     * Cập nhật dữ liệu theo điều kiện.
     *
     * @param array $data Dữ liệu cần cập nhật (cột => giá trị).
     * @param array $conditions Điều kiện WHERE (cột => giá trị).
     * @return bool Trả về true nếu thành công, false nếu thất bại.
     *
     * @example
     * ```php
     * $db->update(['email' => 'newemail@example.com'], ['id' => 1]);
     * ```
     */
    
     public function update(array $data, array $conditions): bool {
         // ✅ Lọc bỏ giá trị là mảng để tránh lỗi "Array to string conversion"
         foreach ($data as $key => $value) {
             if (is_array($value)) {
                 unset($data[$key]); // Xóa bỏ giá trị mảng để tránh lỗi
             }
         }
         
         foreach ($conditions as $key => $value) {
             if (is_array($value)) {
                 unset($conditions[$key]); // Xóa bỏ điều kiện bị lỗi
             }
         }
         
         // ✅ Bọc tên cột bằng dấu backtick ` để tránh lỗi từ khóa SQL
         $setPart = implode(", ", array_map(fn($key) => "`$key` = :$key", array_keys($data)));
         $wherePart = implode(" AND ", array_map(fn($key) => "`$key` = :where_$key", array_keys($conditions)));
         
         $sql = "UPDATE `{$this->table}` SET $setPart WHERE $wherePart";
         
         $stmt = $this->connection->prepare($sql);
         
         foreach ($conditions as $key => $value) {
             $data["where_$key"] = $value;
         }
         
         return $stmt->execute($data);
     }
     
    
    /**
     * Cập nhật nhiều dòng dữ liệu cùng lúc.
     *
     * @param array $data Mảng chứa nhiều dòng cần cập nhật.
     * @param string $conditionColumn Tên cột dùng để xác định dòng cần cập nhật.
     * @return bool Trả về true nếu thành công, false nếu thất bại.
     *
     * @example
     * ```php
     * $data = [
     *       ["id_key" => 1, "name" => "aaaaaaa", "baby" => "nữ", "where" => ["id" => 1]],
     *       ["id_key" => 2, "name" => "bbbbbbb", "baby" => "nam", "where" => ["id" => 2]]
     *   ];
     *   
     *   $db->multiUpdate($data);
     */
   
    public function multiUpdate(array $data): bool {
        if (empty($data)) return false;
        
        $sqlParts = [];
        $params = [];
        
        foreach ($data as $index => $row) {
            if (!isset($row['where']) || !is_array($row['where'])) {
                continue; // Nếu không có điều kiện WHERE, bỏ qua dòng này
            }
            
            $setParts = [];
            foreach ($row as $column => $value) {
                if ($column === 'where') continue; // Bỏ qua điều kiện WHERE
                $paramKey = "{$column}_{$index}";
                $setParts[] = "$column = :$paramKey";
                $params[$paramKey] = $value;
            }
            
            $whereParts = [];
            foreach ($row['where'] as $wCol => $wVal) {
                $whereKey = "{$wCol}_where_{$index}";
                $whereParts[] = "$wCol = :$whereKey";
                $params[$whereKey] = $wVal;
            }
            
            if (!empty($setParts) && !empty($whereParts)) {
                $sqlParts[] = "UPDATE {$this->table} SET " . implode(", ", $setParts) . " WHERE " . implode(" AND ", $whereParts);
            }
        }
        
        if (empty($sqlParts)) return false; // Không có câu lệnh UPDATE nào được tạo
        
        $sql = implode("; ", $sqlParts);
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($params);
    }
    

    /**
     * Xóa dữ liệu theo điều kiện (Hỗ trợ WHERE IN).
     *
     * @param array $conditions Điều kiện WHERE (có thể là `WHERE IN`).
     * @return bool Trả về true nếu thành công, false nếu thất bại.
     *
     * @example
     * ```php
     * $db->delete(['id' => ['IN', [1, 2, 3]]]);
     * ```
     */
    public function delete(array $conditions): bool {
        if (empty($conditions)) return false;

        $whereClauses = [];
        $params = [];

        foreach ($conditions as $key => $value) {
            $safeKey = preg_replace('/[^a-zA-Z0-9_]/', '', $key);
            if (is_array($value)) {
                if ($value[0] === 'IN') {
                    $placeholders = implode(", ", array_map(fn($i) => ":{$safeKey}_{$i}", array_keys($value[1])));
                    $whereClauses[] = "$safeKey IN ($placeholders)";
                    foreach ($value[1] as $index => $val) {
                        $params["{$safeKey}_{$index}"] = $val;
                    }
                }
            } else {
                $whereClauses[] = "$safeKey = :$safeKey";
                $params[$safeKey] = $value;
            }
        }

        $sql = "DELETE FROM {$this->table} WHERE " . implode(" AND ", $whereClauses);
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function __destruct() {
        if ($this->connection instanceof PDO) {
            $this->connection = null;
        }
    }
}
