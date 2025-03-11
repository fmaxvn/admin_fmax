<?php

namespace module\Admin\Controllers;

use Core\Database\DBHandler; // ✅ Load module database
use Core\Helper\Slug;
use Core\Helper\Validator;
use Core\Helper\ViewHelper;

class AppsMarketController extends ViewHelper
{

    private $table = "jp_apps_market";

    public function index()
    {
        $db = new DBHandler($this->table);

        // ✅ **Lấy tham số từ URL**
        $search = $_GET['search'] ?? ''; // Tìm kiếm theo tên
        $filter = $_GET['filter'] ?? ''; // Lọc theo loại domain (1 = VN, 2 = QT)
        $sort = $_GET['sort'] ?? 'desc'; // Sắp xếp (asc/desc)
        $data = array();

        $params = $this->getParams();

        // ✅ **Xây dựng điều kiện truy vấn**
        $conditions = [];
        if (!empty($search)) {
            $conditions['name'] = ['LIKE', "%$search%"];
        }
        if (!empty($filter)) {
            $conditions['type'] = $filter; // '0' = Nền tảng, '1' = Tiện ích
        }

        // ✅ **Tùy chọn sắp xếp**
        $pages = $params["page"] ?? 1;
        $limit = 10; // Số lượng domain trên mỗi trang
        $offset = ((int)$pages - 1) * $limit;

        $options = [
            'order_by' => ["showview desc"],
            'limit' => $limit,
            'offset' => $offset,
            'columns' => 'id, name, app_permission_name, price, range_date, type, showview, description, images, url',
        ];
        // ✅ **Truy vấn danh sách domain**
        $data["listAppsMarket"] = $db->getList($conditions, $options);
        $data["pagination"] = $this->renderPagination(
            $db->countRows($conditions)
        );
        return $this->getLayout($data);
    }

    /**
     * Hàm thêm mới
     * @return string
     */
    public function add()
    {
        $view = new ViewHelper();
        $data = [];

        // Xử lý form submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new DBHandler($this->table);
            // Validate dữ liệu
            $validator = new Validator();
            $validator->validate($_POST, [
                'name' => ['required'],
                'price' => ['required', 'numeric'],
                'type' => ['required', 'in:0,1,2'],
                'description' => ['required'],
            ]);

            if ($validator->fails()) {
                $data['errors'] = $validator->errors();
                $data['old'] = $_POST; // Giữ lại dữ liệu đã nhập
                return $view->getLayout($data);
            }

            $_POST['range_date'] = 12;
            // Insert trực tiếp dữ liệu POST vào database
            if ($db->insert($_POST)) {
                $data['success'] = "Thêm mới domain thành công.";
            } else {
                $data['error'] = "Có lỗi xảy ra khi thêm mới. Vui lòng thử lại.";
                $data['old'] = $_POST; // Giữ lại dữ liệu đã nhập
            }
        }

        return $view->getLayout($data);
    }

    public function edit()
    {
        $db = new DBHandler($this->table);

        $view = new ViewHelper();
        $params = $this->getParams();
        $appsMarketId = $params['id'];
        // ✅ **Truy vấn danh sách thành viên với các cột cần thiết**
        $appsMarketDetail = $db->getOne(['id' => $appsMarketId]);

        if (!$appsMarketDetail) {
            return $view->getLayout(['error' => "Tiện ích không tồn tại"]);
        }
        // ✅ Nếu form được submit (POST request)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new Validator();
            $validator->validate($_POST, [
                'name' => ['required'],
                'price' => ['required', 'numeric'],
                'type' => ['required', 'in:0,1'],
            ]);

            // Kiểm tra nếu có lỗi
            if ($validator->fails()) {
                $data['errors'] = $validator->errors(); // ✅ Truyền danh sách lỗi ra view
                $data['old'] = $_POST; // ✅ Giữ lại dữ liệu đã nhập
                return $view->getLayout(array_merge(['appsMarketDetail' => $appsMarketDetail], $data));
            }
            // 'columns' => 'id, name, app_permission_name, price, range_date, type, showview, description, images, url',

            // Lấy toàn bộ dữ liệu từ form
            $fields = [
                'name',
                'price',
                'type',
                'url',
                'description',
                'template',
            ];

            $updateData = [];

            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    $updateData[$field] = $_POST[$field];
                }
            }

            // ✅ Cập nhật dữ liệu vào database
            if (!empty($updateData)) {
                $success = $db->update($updateData, ['id' => $appsMarketId]);
                if ($success) {
                    $appsMarketDetail = array_merge($appsMarketDetail, $updateData); // Cập nhật dữ liệu mới 
                    return $view->getLayout([
                        'appsMarketDetail' => $appsMarketDetail,
                        'success' => "Cập nhật thành công"
                    ]);
                }
            }

            return $view->getLayout([
                'appsMarketDetail' => $appsMarketDetail,
                'error' => "Không có dữ liệu nào được cập nhật"
            ]);
        }

        $data['appsMarketDetail'] = $appsMarketDetail;
        // ✅ Nếu chỉ là GET request, hiển thị form chỉnh sửa
        return $view->getLayout($data);
    }
    public function toggleStatus()
    {

        // Kết nối Database
        $db = new DBHandler($this->table);

        // Kiểm tra phương thức POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
            exit();
        }

        // Lấy dữ liệu JSON từ request
        $input = json_decode(file_get_contents("php://input"), true);

        // Kiểm tra dữ liệu hợp lệ
        if (!isset($input['id']) || !isset($input['showview'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit();
        }

        $memberId = (int) $input['id'];
        $newStatus = (int) $input['showview'];


        // Kiểm tra user tồn tại
        $member = $db->getOne(['id' => $memberId]);
        if (!$member) {
            http_response_code(404); // Not Found
            echo json_encode(['success' => false, 'message' => 'Người dùng không tồn tại']);
            exit();
        }

        // Cập nhật trạng thái `showview`
        $updateStatus = $db->update(['showview' => $newStatus], ['id' => $memberId]);

        if ($updateStatus) {
            echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật']);
        }

        exit();
    }

    /**
     * Hàm convert slug
     * @return never
     */
    public function convertSlug()
    {
        header('Content-Type: application/json'); // Đảm bảo trả về JSON

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ']);
            exit();
        }

        if (empty($_POST['name_convert'])) {
            echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
            exit();
        }

        $slug = Slug::createSlug($_POST['name_convert']);

        echo json_encode(['status' => 'success', 'slug' => $slug]);
        exit();
    }

    /**
     * Hàm Xóa
     */
    public function delete()
    {
        // Chỉ chấp nhận POST request để tránh CSRF
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 403 Forbidden');
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            exit;
        }
        $objId = $_POST['id'] ?? '';
        $db = new DBHandler($this->table);

        // Lấy thông tin user để xóa avatar nếu có
        $objDetail = $db->getOne(['id' => $objId]);

        if ($objDetail && $db->delete(['id' => $objId])) {
            echo json_encode(['status' => 'success', 'message' => 'Xóa user thành công.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra khi xóa.']);
        }
        exit;
    }
}
