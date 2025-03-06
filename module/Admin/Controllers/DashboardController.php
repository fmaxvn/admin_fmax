<?php

namespace module\Admin\Controllers;

use Core\Database\DBHandler; // ✅ Load module database

use Core\Helper\ViewHelper;

class DashboardController {
    
    private $table = "jp_member";
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
        
        $member = new DBHandler($this->table);
        
        
        // ✅ **Lấy tham số từ URL**
        $search = $_GET['search'] ?? ''; // Tìm kiếm theo tên
        $sort = $_GET['sort'] ?? 'desc'; // Sắp xếp (asc/desc)
        $limit = 10; // Số lượng người dùng trên mỗi trang
        $pages = 1;
        $offset = ((int)$pages - 1) * $limit;
        
        
        
        $data = array();
        
        // ✅ **Xây dựng điều kiện truy vấn**
        $conditions = [];
        if (!empty($search)) {
            $conditions['fullname'] = ['LIKE', "%$search%"];
        }
        
        // ✅ **Tùy chọn sắp xếp**
        $options = [
            'order_by' => ["datecreate $sort"],
            'limit' => $limit,
            'offset' => $offset,
            'columns' => 'id, fullname, email, mobile, datecreate, online',
        ];
        
        $data["users"] = $member->getList($conditions, $options);
        
        $view = new ViewHelper();
        $data["pagination"] = $view->renderPagination(1000);
        //         $view->is_ajax = true;
        return $view->getLayout($data);
    }
    
    
}