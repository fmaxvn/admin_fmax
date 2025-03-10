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

        // ✅ Kiểm tra nếu `sort` không hợp lệ, đặt mặc định là `DESC`
        $sort = in_array(strtolower($sort), ['asc', 'desc']) ? strtoupper($sort) : 'DESC';

        $data = array();
        $params = $this->getParams();

        // ✅ **Xây dựng điều kiện truy vấn**
        $conditions = [];
        if (!empty($search)) {
            $conditions['jp_domain.domain'] = ['LIKE', "%$search%"];
        }

        // ✅ **Tùy chọn sắp xếp**
        $pages = !empty($params["page"]) ? (int)$params["page"] : 1;
        $limit = 10; // Số lượng domain trên mỗi trang
        $offset = ((int)$pages - 1) * $limit;

        $options = [
            'columns' => 'jp_domain.id,domain, database_name, type_template, folder , jp_template.name AS template_name',
            'order_by' => ["datecreate $sort"],
            'limit' => $limit,
            'offset' => $offset,
            'joins' => [
                ['LEFT JOIN', 'jp_template', 'jp_template.id = jp_domain.type_template'] // ✅ JOIN với jp_template
            ]
        ];
        
        // ✅ **Truy vấn danh sách thành viên với các cột cần thiết**
        $data["domain"] = $db->getList($conditions, $options);
        $data["pagination"] = $this->renderPagination($db->countRows($conditions));

        return $this->getLayout($data);
    }
    
    public function edit() {
        $db = new DBHandler($this->table);
        $view = new ViewHelper();
        $params = $this->getParams();

        // ✅ Kiểm tra ID hợp lệ
        if (empty($params['id']) || !is_numeric($params['id'])) {
            return $view->getLayout(['error' => "ID domain không hợp lệ"]);
        }

        $domainId = (int) $params['id'];

        // ✅ Lấy danh sách templates từ bảng jp_template
        $templateDB = new DBHandler('jp_template');
        $templates = $templateDB->getList([], ['columns' => 'id, name']);

        // ✅ Lấy thông tin domain từ database
        $domain = $db->getOne(['id' => $domainId]);

        if (!$domain) {
            return $view->getLayout(['error' => "Domain không tồn tại"]);
        }

        // ✅ Nếu form được submit (tức là phương thức POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Danh sách cột cần cập nhật
            $fields = ['domain', 'database_name', 'type_template', 'folder'];

            $updateData = [];

            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    $updateData[$field] = trim($_POST[$field]); // ✅ Loại bỏ khoảng trắng thừa
                }
            }

            // ✅ Kiểm tra nếu có dữ liệu để cập nhật
            if (!empty($updateData)) {
                $success = $db->update($updateData, ['id' => $domainId]);

                if ($success) {
                    // ✅ Cập nhật lại dữ liệu domain trong biến $domain
                    $domain = array_merge($domain, $updateData);

                    return $view->getLayout([
                        'domain' => $domain,
                        'templates' => $templates,
                        'success' => "Cập nhật domain thành công"
                    ]);
                } else {
                    return $view->getLayout([
                        'domain' => $domain,
                        'templates' => $templates,
                        'error' => "Cập nhật thất bại, vui lòng thử lại"
                    ]);
                }
            }

            return $view->getLayout([
                'domain' => $domain,
                'templates' => $templates,
                'error' => "Không có dữ liệu nào được cập nhật"
            ]);
        }

        // ✅ Nếu chỉ là GET request, hiển thị form chỉnh sửa
        return $view->getLayout([
            'domain' => $domain,
            'templates' => $templates
        ]);
    }

}