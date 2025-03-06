<?php

namespace module\Admin\Controllers;

use Core\Database\DBHandler; // ✅ Load module database

use Core\Helper\ImageUploader;
use Core\Helper\ViewHelper;

class UserController extends ViewHelper{
    
    private $table = "jp_user";
    /**
     * Hiển thị danh sách thành viên với phân trang & tìm kiếm.
     *
     * @param int $pages Số trang hiện tại (Mặc định = 1)
     *
     * @example
     * ```php
     * https://nangcap.hdcode.name.vn/admin/user/index/pages/1?search=John&sort=asc
     * ```
     */
    public function index() {
        
        $db = new DBHandler($this->table);
        
        // ✅ **Lấy tham số từ URL**
        $search = $_GET['search'] ?? ''; // Tìm kiếm theo tên
        $sort = $_GET['sort'] ?? 'desc'; // Sắp xếp (asc/desc)
        $data = array();
        
        $params = $this->getParams();

        // ✅ **Xây dựng điều kiện truy vấn**
        $conditions = [];
        if (!empty($search)) {
            $conditions['fullname'] = ['LIKE', "%$search%"];
        }
        
        // ✅ **Tùy chọn sắp xếp**
        $pages = 1;
        if(!empty($params["page"])){
            $pages = $params["page"];
        }
        $limit = 10; // Số lượng người dùng trên mỗi trang
        $offset = ((int)$pages - 1) * $limit;
        
        $options = [
            'order_by' => ["datecreate $sort"],
            'limit' => $limit,
            'offset' => $offset,
            'columns' => 'id, fullname, email, mobile, datecreate, online, avatar',
        ];
        
        // ✅ **Truy vấn danh sách thành viên với các cột cần thiết**
        $data["users"] = $db->getList($conditions, $options);
        
        $data["pagination"] = $this->renderPagination($db->countRows($conditions));
//         $view->is_ajax = true;
        return $this->getLayout($data);
    }
    
    
    public function edit() {
        $db = new DBHandler($this->table);
        $view = new ViewHelper();
        $params = $this->getParams();
        
        $userId = $params['id'];
        // Lấy thông tin người dùng từ database
        $user = $db->getOne(['id' => $userId]);
        
        if (!$user) {
            return $view->getLayout(['error' => "Người dùng không tồn tại"]);
        }
        
        // ✅ Nếu form được submit (tức là phương thức POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy toàn bộ dữ liệu từ form
            $fields = [
                'fullname', 'email', 'mobile', 'address', 'notes',
                'facebook', 'youtube', 'twitter', 'instagram', 'desc', 'id_usergroup', 'sex', 'is_team'
            ];
            $updateData = [];
            
            foreach ($fields as $field) {
                if (!empty($_POST[$field])) {
                    $updateData[$field] = $_POST[$field];
                }
            }
            
            // ✅ Upload avatar nếu có
            if (!empty($_FILES['avatar']['name'])) {
                $uploadedAvatar = ImageUploader::upload($_FILES['avatar'], ["50x50","100x100"]);
                if ($uploadedAvatar) {
                    $updateData['avatar'] = $uploadedAvatar;
                }
            }
            
            // ✅ Cập nhật dữ liệu vào database
            if (!empty($updateData)) {
                $success = $db->update($updateData, ['id' => $userId]);
                if ($success) {
                    $user = array_merge($user, $updateData); // Cập nhật dữ liệu mới vào biến user
                    return $view->getLayout([
                        'user' => $user,
                        'success' => "Cập nhật người dùng thành công"
                    ]);
                }
            }
            
            return $view->getLayout([
                'user' => $user,
                'error' => "Không có dữ liệu nào được cập nhật"
            ]);
        }
        
        // ✅ Nếu chỉ là GET request, hiển thị form chỉnh sửa
        return $view->getLayout([
            'user' => $user
        ]);
    }
    
    
    
    
    /**
     * ✅ Hiển thị trang Profile
     */
    public function profile() {
        $view = new ViewHelper();
        $db = new DBHandler($this->table);
        
        // Lấy ID user từ session
        $userId = $_SESSION['user_id'];
        $data['user'] = $db->getOne(['id' => $userId]);
        
        // Nếu user không tồn tại
        if (!$data['user']) {
            $data['error'] = "Tài khoản không tồn tại.";
            return $view->getLayout($data);
        }
        
        // ✅ Xử lý cập nhật thông tin nếu có POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updateData = $_POST; // ✅ Lấy toàn bộ dữ liệu từ form
            
            // ✅ Upload avatar nếu có
            if (!empty($_FILES['avatar']['name'])) {
                $upload = new ImageUploader();
                $avatarName = $upload->upload($_FILES['avatar'], array("100x100")); // Hàm upload ảnh
                if ($avatarName) {
                    $updateData['avatar'] = $avatarName;
                } else {
                    $data['error'] = "Lỗi khi upload ảnh.";
                    return $view->getLayout($data);
                }
            }
            
            // ✅ Thực hiện cập nhật dữ liệu nếu có thay đổi
            if (!empty($updateData)) {
                if ($db->update($updateData, ['id' => $userId])) {
                    $data['success'] = "Cập nhật thông tin thành công.";
                    $data['user'] = $db->getOne(['id' => $userId]); // Lấy lại thông tin mới
                } else {
                    $data['error'] = "Không có thay đổi hoặc cập nhật thất bại.";
                }
            }
        }
        
        return $view->getLayout($data);
    }
    
    /**
     * Hiển thị trang đổi mật khẩu và xử lý đổi mật khẩu khi submit
     */
    public function change_password() {
        
        $view = new ViewHelper();
        $data = array();
        
        // Nếu là phương thức POST, xử lý đổi mật khẩu
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new DBHandler($this->table);
            
            $userId = $_SESSION['user_id'] ?? null;
            $oldPassword = $_POST['old_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Kiểm tra user tồn tại
            $user = $db->getOne(['id' => $userId]);
            
            if (!$user) {
                $data['error'] = "Người dùng không tồn tại.";
                return $view->getLayout($data);
            }
            
            // Kiểm tra mật khẩu cũ
            $hashedOldPassword = md5($oldPassword . TOKEN);
            if ($hashedOldPassword !== $user['password']) {
                $data['error'] = "Mật khẩu cũ không đúng.";
                return $view->getLayout($data);
            }
            
            // Kiểm tra mật khẩu mới
            if (strlen($newPassword) < 6) {
                $data['error'] = "Mật khẩu mới phải có ít nhất 6 ký tự.";
                return $view->getLayout($data);
            }
            
            if ($newPassword !== $confirmPassword) {
                $data['error'] = "Mật khẩu xác nhận không khớp.";
                return $view->getLayout($data);
            }
            
            // Cập nhật mật khẩu mới
            $hashedNewPassword = md5($newPassword . $user['token']);
            $updateStatus = $db->update(['password' => $hashedNewPassword], ['id' => $userId]);
            
            if ($updateStatus) {
                $data['success'] = "Đổi mật khẩu thành công.";
            } else {
                $data['error'] = "Có lỗi xảy ra khi đổi mật khẩu.";
            }
        }
        
        return $view->getLayout($data);
    }
    
    
}