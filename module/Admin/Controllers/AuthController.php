<?php

namespace module\Admin\Controllers;

use Core\Database\DBHandler;
use Core\Helper\ViewHelper;

class AuthController {
    private $table = "jp_user"; // Bảng admin

    /**
     * Xử lý đăng nhập với MD5 + token
     */
    public function login() {
        $view = new ViewHelper();
        $data = array();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $db = new DBHandler($this->table);

            // ✅ **Xây dựng điều kiện truy vấn**
            $conditions = ['username' => $username];
            
            // ✅ **Truy vấn các cột cần thiết**
            
            $options = array();
            $options["columns"] = 'id, username, password';
            $data["user"] = $db->getOne($conditions, $options);
            if (!empty($data["user"])) {
                if (password_verify($password, $data["user"]['password'])) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['user_id'] = $data["user"]['id'];
                    header("Location: /admin/dashboard/index");
                    exit;
                }
            }
            $data['error'] = "Sai tài khoản hoặc mật khẩu.";
            $view->is_ajax = true;
            return $view->getLayout($data);
        }
        $view->is_ajax = true;
        return $view->getLayout($data);
    }

    /**
     * Đăng xuất
     */
    public function logout() {
        session_start();
        session_destroy();
        header("Location: /admin/auth/login");
        exit;
    }
}

