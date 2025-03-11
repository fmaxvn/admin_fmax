<?php

namespace module\Admin\Controllers;

use Core\Database\DBHandler; // ✅ Load module database
use Core\Helper\Validator;
use Core\Helper\ViewHelper;

class PaymentShippingController extends ViewHelper
{

    private $table = "jp_payment_shipping";
    private $tableDomain = "jp_domain";
    private $tableMember = "jp_member";

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
            // $conditions['domain'] = ['LIKE', "%$search%"];
        }
        if (!empty($filter)) {
            $conditions['id_dvvc'] = $filter; // '4' = Đi bộ, '1' = GTTK
        }

        // ✅ **Tùy chọn sắp xếp**
        $pages = $params["page"] ?? 1;
        $limit = 10; // Số lượng domain trên mỗi trang
        $offset = ((int)$pages - 1) * $limit;

        $options = [
            'order_by' => ["type asc, id asc"],
            'limit' => $limit,
            'offset' => $offset,
            'columns' => 'jp_payment_shipping.*, jp_domain.domain AS domain, jp_member.username AS username, jp_member.fullname AS fullname', // Định danh id cụ thể
            'joins' => [
                ['LEFT JOIN', 'jp_domain', 'jp_domain.id = jp_payment_shipping.id_domain'], // JOIN bảng jp_domain
                ['LEFT JOIN', 'jp_member', 'jp_member.id = jp_payment_shipping.id_member'] // JOIN bảng jp_member
            ]
        ];
        // ✅ **Truy vấn danh sách domain**
        $data["listPaymentShipping"] = $db->getList($conditions, $options);
        $data["pagination"] = $this->renderPagination(
            $db->countRows($conditions)
        );
        return $this->getLayout($data);
    }



    public function edit()
    {
        $db = new DBHandler($this->table);
        $dbDomain = new DBHandler($this->tableDomain);

        $view = new ViewHelper();
        $params = $this->getParams();
        $appsMarketId = $params['id'];
        // ✅ **Truy vấn danh sách thành viên với các cột cần thiết**
        $paymentShippingDetail = $db->getOne(['id' => $appsMarketId]);
        $data['infoDomainPayment'] = $dbDomain->getOne(['id' => $paymentShippingDetail['id_domain']], ['columns' => 'id, domain, public_website']);

        if (!$paymentShippingDetail) {
            return $view->getLayout(['error' => "Tiện ích không tồn tại"]);
        }
        // ✅ Nếu form được submit (POST request)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new Validator();
            $validator->validate($_POST, [
                'name' => ['required'],
                'total' => ['required', 'numeric'],
            ]);
            // Kiểm tra nếu có lỗi
            if ($validator->fails()) {
                $data['errors'] = $validator->errors(); // ✅ Truyền danh sách lỗi ra view
                $data['old'] = $_POST; // ✅ Giữ lại dữ liệu đã nhập
                return $view->getLayout(array_merge(['paymentShippingDetail' => $paymentShippingDetail], $data));
            }
            // 'columns' => 'id, name, app_permission_name, price, range_date, type, showview, description, images, url',

            // Lấy toàn bộ dữ liệu từ form
            $fields = [
                'name',
                'total'
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
                    $data['paymentShippingDetail'] = array_merge($paymentShippingDetail, $updateData); // Cập nhật dữ liệu mới 
                    return $view->getLayout($data);
                }
            }

            return $view->getLayout($data);
        }

        $data['paymentShippingDetail'] = $paymentShippingDetail;
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

    public function export()
    {
        // ✅ Kết nối Database
        $db = new DBHandler($this->table);

        // ✅ Lấy tham số từ URL
        $search = $_GET['search'] ?? ''; // Tìm kiếm theo tên
        $sort = $_GET['sort'] ?? 'desc'; // Sắp xếp (asc/desc)
        $filter = $_GET['filter'] ?? '';
        // ✅ Xây dựng điều kiện truy vấn
        $conditions = [];
        if (!empty($search)) {
            $conditions['code'] = ['LIKE', "%$search%"];
        }
        if (!empty($filter)) {
            $conditions['id_dvvc'] = $filter; // '4' = Đi bộ, '1' = GTTK
        }

        $options = [
            'order_by' => ["type asc, id asc"],
            'columns' => 'jp_payment_shipping.*, jp_domain.domain AS domain, jp_member.username AS username, jp_member.fullname AS fullname', // Định danh id cụ thể
            'joins' => [
                ['LEFT JOIN', 'jp_domain', 'jp_domain.id = jp_payment_shipping.id_domain'], // JOIN bảng jp_domain
                ['LEFT JOIN', 'jp_member', 'jp_member.id = jp_payment_shipping.id_member'] // JOIN bảng jp_member
            ]
        ];
        // ✅ **Truy vấn danh sách domain**
        $listPaymentShipping = $db->getList($conditions, $options);
        // ✅ Header để trình duyệt nhận diện là file Excel `.xlsx`
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=DanhSach_DonHang_DVVC.xls");
        header("Cache-Control: max-age=0");

        // ✅ Mở output stream
        $output = fopen('php://output', 'w');

        // ✅ Thêm UTF-8 BOM để tránh lỗi font
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // ✅ Tạo bảng HTML để Excel nhận diện
        echo "<html xmlns:x='urn:schemas-microsoft-com:office:excel'>";
        echo "<head>";
        echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>";
        echo "<style>
                body { font-family: Arial, sans-serif; }
                table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; }
                th, td { border: 1px solid black; padding: 8px; text-align: left; font-family: Arial, sans-serif; }
                th { background-color: #B3D9FF; font-weight: bold; font-size: 14px; font-family: Arial, sans-serif; }
              </style>";
        echo "</head>";
        echo "<body>";
        echo "<table>";

        // ✅ Xuất tiêu đề cột
        echo "<tr>";
        echo "<th>STT</th>";
        echo "<th>Mã thanh toán</th>";
        echo "<th>Mã đơn hàng đăng đơn</th>";
        echo "<th>Loại</th>";
        echo "<th>Tổng tiền</th>";
        echo "<th>Đăng đơn</th>";
        echo "<th>Website</th>";
        echo "<th>Tên khách hàng</th>";
        echo "<th>Tài khoản</th>";
        echo "</tr>";

        // ✅ Xuất dữ liệu
        $stt = 1;
        foreach ($listPaymentShipping as $row) {
            $orderStatus = !empty($row['order_status']) ? "Đã đăng đơn" : "Chưa đăng đơn";
            echo "<tr>";
            echo "<td>{$stt}</td>";
            echo "<td>{$row['code_shipping']}</td>";
            echo "<td>{$row['code_cart']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['total']}</td>";
            echo "<td style='text-align: right;'>{$orderStatus}</td>";
            echo "<td>{$row['domain']}</td>";
            echo "<td>{$row['fullname']}</td>";
            echo "<td>{$row['username']}</td>";
            echo "</tr>";
            $stt++;
        }

        echo "</table>";
        echo "</body>";
        echo "</html>";

        fclose($output);
        exit();
    }
}
