<?php

namespace Core;

use Core\Helper\ViewHelper;

class Router {
    public function dispatch() {
        
        $router = new ViewHelper();
        $infoRouter = $router->getRouter();
        $method     = $infoRouter["method"];
        $params     = $infoRouter["params"];
        $controllerPath = $infoRouter["controllerPath"];
        $controllerClass = $infoRouter["controllerClass"];

        if(empty($_SESSION['admin_logged_in']) && $params[1] != "auth"){
            header("Location: /admin/auth/login");
            exit;
        }
        
        // Debug đường dẫn để kiểm tra nếu vẫn lỗi
        if (!file_exists($controllerPath)) {
            http_response_code(500);
            echo "Error: Controller file not found: $controllerPath";
            exit();
        }

        require_once $controllerPath;

        if (!class_exists($controllerClass)) {
            http_response_code(500);
            echo "Error: Class not found: $controllerClass";
            exit();
        }
        
        $instance = new $controllerClass();
        if (method_exists($instance, $method)) {
            return call_user_func_array([$instance, $method], $params);
        } else {
            http_response_code(404);
            echo "404 - Method '$method' not found in '$controllerClass'";
        }
    }
}


