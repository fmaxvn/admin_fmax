<?php

namespace Core\Helper;

class ResponseHelper {
    /**
     * Xử lý dữ liệu và trả về JSON
     *
     * @param mixed $data Dữ liệu cần trả về
     * @param string|null $message Thông báo (tự động xác định nếu không truyền vào)
     * @param int|null $statusCode HTTP Status Code (tự động xác định nếu không truyền vào)
     * @return string Chuỗi JSON
     *
     * @example
     * ```php
     * return ResponseHelper::handle($users);
     * ```
     */
    public static function handle($data, ?string $message = null, ?int $statusCode = null): string {
        // Nếu dữ liệu rỗng hoặc null, tự động trả về lỗi
        if (empty($data)) {
            return self::json([
                'status' => 'error',
                'message' => $message ?? 'Không tìm thấy dữ liệu'
            ], $statusCode ?? 404);
        }
        
        // Nếu có dữ liệu, tự động trả về thành công
        return self::json([
            'status' => 'success',
            'message' => $message ?? 'Thành công',
            'data' => $data
        ], $statusCode ?? 200);
    }
    
    /**
     * Trả về JSON response
     *
     * @param mixed $data Dữ liệu cần trả về
     * @param int $statusCode Mã HTTP response
     * @return string Chuỗi JSON
     */
    private static function json($data, int $statusCode): string {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
