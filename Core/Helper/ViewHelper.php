<?php

namespace Core\Helper;

class ViewHelper {
    /**
     * Lấy đường dẫn layout và view tự động
     *
     * @param string $className Tên class Controller
     * @param string $method Tên method đang chạy
     * @return array Trả về mảng ['layoutPath' => ..., 'viewPath' => ...]
     */
    
    public $is_ajax = false;
    
    /**
     * Trả về link ảnh đầy đủ từ tên file, nếu không tồn tại trả về ảnh mặc định
     *
     * @param string $fileName Tên file ảnh (định dạng: {timestamp}-{tên}.jpg)
     * @param string $size Kích thước ảnh (tùy chọn, ví dụ: '200x200', '400x400')
     * @return string URL ảnh đầy đủ hoặc ảnh mặc định
     *
     * Ví dụ:
     * getImageUrl('1709578200-product.jpg') 
     * -> Trả về: "https://example.com/uploads/2024/03/04/1709578200-product.jpg" (nếu file tồn tại)
     * -> Hoặc: "https://example.com/uploads/default.jpg" (nếu file không tồn tại)
     *
     * getImageUrl('1709578200-product.jpg', '200x200') 
     * -> Trả về: "https://example.com/uploads/2024/03/04/200x200-1709578200-product.jpg" (nếu file tồn tại)
     * -> Hoặc: "https://example.com/uploads/default.jpg" (nếu file không tồn tại)
     */
    public function getImageUrl(string $fileName, string $size = ''): string {
        if (!preg_match('/^(\d+)-/', $fileName, $matches)) {
            return URL_DEFAULT_IMAGE; // Trả về ảnh mặc định nếu tên file không hợp lệ
        }
    
        $timestamp = (int) $matches[1];
        $datePath = date("Y/m/d", $timestamp);
        
        // Nếu có kích thước, đổi tên file thành {size}-{tên gốc}.jpg
        $fileNameWithSize = $size ? "$size-$fileName" : $fileName;
    
        $imagePath = URL_UPLOADS . "/$datePath/$fileNameWithSize";
        $filePath = UPLOADS_DIR . "/$datePath/$fileNameWithSize";
    
        // Kiểm tra nếu file tồn tại, nếu không trả về ảnh mặc định
        return file_exists($filePath) ? $imagePath : URL_DEFAULT_IMAGE;
    }
    
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
    public static function responseApi($data, ?string $message = null, ?int $statusCode = null): string {
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
    
    public function renderPagination(int $totalRecords,
        int $recordsPerPage = 10, int $visiblePages = 5): string {
        
        $totalPages = ceil($totalRecords / $recordsPerPage);
        if ($totalPages <= 1){
            return '';
        }
        // ✅ **Lấy đường dẫn file phân trang**
        $paginationPath = CORE_PATH . "/Helper/pagination.php";
        // ✅ **Truyền dữ liệu vào View**
        ob_start();
        if (file_exists($paginationPath)) {
            
            $baseUrl = $this->getUrl();
            $param = $this->getParams();
            if(empty($param["params"][4])){
                $currentPage = 1;
            }else{
                $currentPage = (int)$param["params"][4];
            }
            require $paginationPath;
        } else {
            echo "Lỗi: Không tìm thấy file phân trang!";
        }
        $html = ob_get_clean();
        return $html;
    }
    
    public function getLayout($data, $array = array()): string{
        
        $param = $this->getParams();
        if(empty($array["controller"]) && empty($array["method"]) ){
            $array["controller"] = $param['controller'];
            $array["method"] = $param['method'];
        }
        $viewPath = BASE_PATH . "/module/Admin/Views/{$array["controller"]}/{$array["method"]}.php";
        extract($data);
        if($this->is_ajax == true){
            require $viewPath;
            return true;
        }
        if(empty($array["layoutPath"])){
            $array["layoutPath"] = BASE_PATH . "/module/Admin/Views/layout/layout.php";
        }
        if (file_exists($array["layoutPath"])) {
            require $array["layoutPath"];
        } else {
            return "Lỗi: Không tìm thấy file Layout.";
        }
        return true;
    }
    
    public function getRouter(): array {
        
        // ✅ **Tìm Controller trong `/module/Admin/Controllers/`**
        $param      = $this->getParams();
        
        $module     = $param['module'];
        $controller = $param['controller'];
        $method     = $param['method'];
        $param_routuer      = $param['params'];
        
        return [
                "method" => $method,
                "params" => $param_routuer,
                "controllerPath" => BASE_PATH . "/module/$module/Controllers/{$controller}Controller.php",
                "controllerClass" => "module\\$module\\Controllers\\{$controller}Controller"
            ];
    }
    
    public function getUrl(): string {
        $param = $this->getParams();
        $urlParts = [];
        
        // ✅ **Thêm module nếu có (VD: admin)**
        if (!empty($param["params"][0])) {
            $urlParts[] = $param["params"][0];
        }
        
        // ✅ **Thêm controller**
        if (!empty($param["params"][1])) {
            $urlParts[] = $param["params"][1];
        }
        
        // ✅ **Thêm method**
        if (!empty($param["params"][2])) {
            $urlParts[] = $param["params"][2];
        }
        // ✅ **Ghép thành URL chuẩn**
        return BASE_URL . '/' . implode('/', $urlParts);
    }
    
    private function toCamelCase($string) {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $string))));
    }
    
    public function getParams(): array{
        // Lấy đường dẫn đầy đủ từ REQUEST_URI
        $uri = $_SERVER['REQUEST_URI'];
        
        // Loại bỏ `base_url` nếu có
        $scriptName = str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']);
        $uri = str_replace($scriptName, '', $uri);
        $uri = trim(parse_url($uri, PHP_URL_PATH), '/');
        
        // Tách các phần của URL thành mảng
        $uriParts = explode('/', $uri);
        $params = [];
        
        // Mặc định giá trị controller, method
        $module = '';
        $controller = 'Home';
        $method = 'index';
        
        // ✅ **Xác định module, controller, method & params**
        if (!empty($uriParts[0]) && $uriParts[0] === 'admin') {
            $module = 'Admin';
            $params[] = $uriParts[0];
            array_shift($uriParts);
        }
        
        if (!empty($uriParts[0])) {
            $controller = ucfirst($uriParts[0]);
            $controller = str_replace(' ', '', ucwords(str_replace('-', ' ', $controller)));
            $params[] = $uriParts[0];
            array_shift($uriParts);
        }
        
        if (!empty($uriParts[0])) {
            $method = $this->toCamelCase($uriParts[0]);
            $params[] = $uriParts[0];
            array_shift($uriParts);
        }
        
        // ✅ **Lấy các tham số còn lại**
        while (!empty($uriParts)) {
            $params[] = array_shift($uriParts);
        }
        $return = [
                    "params" => $params,
                    "module" => $module,
                    "controller" => $controller,
                    "method" => $method
                ];
        if (!empty($params[3]) && !empty($params[4])) {
            $return[$params[3]] = $params[4];
        }
        return $return;
    }
    
    
}
