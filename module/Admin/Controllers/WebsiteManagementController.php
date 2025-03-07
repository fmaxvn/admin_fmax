<?php

namespace module\Admin\Controllers;

use Core\Database\DBHandler; // ✅ Load module database

use Core\Helper\ViewHelper;

class WebsiteManagementController extends ViewHelper{
    
    private $table = "jp_domain";

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
        $limit = 10; // Số lượng domain trên mỗi trang
        $offset = ((int)$pages - 1) * $limit;

        $options = [
            'order_by' => ["datecreate $sort"],
            'limit' => $limit,
            'offset' => $offset,
            'columns' => 'domain, database_name, id_pcngroup, type_template, domain_old',
        ];
        
        // ✅ **Truy vấn danh sách thành viên với các cột cần thiết**
        $data["domain"] = $db->getList($conditions, $options);
        
        $data["pagination"] = $this->renderPagination($db->countRows($conditions));
//         $view->is_ajax = true;
        return $this->getLayout($data);
    }
    
    public function edit() {
        $db = new DBHandler($this->table);
        $view = new ViewHelper();
        $params = $this->getParams();
        
        $domainId = $params['id'];
        // Lấy thông tin domain từ database
        $domain = $db->getOne(['id' => $domainId]);
        
        if (!$domain) {
            return $view->getLayout(['error' => "domain không tồn tại"]);
        }
        
        // ✅ Nếu form được submit (tức là phương thức POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy toàn bộ dữ liệu từ form
            $fields = [
                'domain', 'database_name', 'id_pcngroup', 'type_template', 'domain_old'
            ];

            $updateData = [];
            
            foreach ($fields as $field) {
                if (!empty($_POST[$field])) {
                    $updateData[$field] = $_POST[$field];
                }
            }

            // ✅ Cập nhật dữ liệu vào database
            if (!empty($updateData)) {
                $success = $db->update($updateData, ['id' => $domainId]);
                if ($success) {
                    $domain = array_merge($domain, $updateData); // Cập nhật dữ liệu mới vào biến domain
                    return $view->getLayout([
                        'domain' => $domain,
                        'success' => "Cập nhật domain thành công"
                    ]);
                }
            }
            
            return $view->getLayout([
                'domain' => $domain,
                'error' => "Không có dữ liệu nào được cập nhật"
            ]);
        }
        
        // ✅ Nếu chỉ là GET request, hiển thị form chỉnh sửa
        return $view->getLayout([
            'domain' => $domain
        ]);
    }
    

    
    
}