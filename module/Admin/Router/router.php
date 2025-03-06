<?php

namespace Core\Routing;

use Core\Auth\AdminAuth;
use Core\Http\Request;
use Core\Http\Response;

class AdminRouter {
    private string $namespace = "Module\\Admin\\Controllers\\"; // Namespace mới để phù hợp với thư mục module/admin/

    public function __construct() {
        // Kiểm tra quyền admin trước khi truy cập
        AdminAuth::checkAuth();
    }

    public function dispatch() {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $segments = explode('/', $uri);

        // Kiểm tra định dạng URL: /admin/{controller}/{action}
        if (count($segments) < 3 || $segments[0] !== 'admin') {
            Response::json(['error' => 'Invalid admin route'], 404);
            return;
        }

        $module = strtolower($segments[0]); // Lấy module (admin)
        $controllerName = ucfirst(strtolower($segments[1])) . "Controller"; // Controller
        $actionName = strtolower($segments[2]); // Action (method)

        // Xác định đường dẫn của Controller
        $controllerClass = $this->namespace . $controllerName;

        // Kiểm tra xem Controller có tồn tại không
        if (!class_exists($controllerClass)) {
            Response::json(['error' => "Controller '$controllerClass' not found"], 404);
            return;
        }

        $controllerInstance = new $controllerClass();

        // Kiểm tra xem phương thức (action) có tồn tại không
        if (!method_exists($controllerInstance, $actionName)) {
            Response::json(['error' => "Action '$actionName' not found in '$controllerClass'"], 404);
            return;
        }

        // Gọi action trong Controller
        call_user_func([$controllerInstance, $actionName]);
    }
}

// Khởi động router
$router = new AdminRouter();
$router->dispatch();
