<?php

namespace module\Admin\Controllers;

use Core\Database\DBHandler; // ✅ Load module database
use Core\Helper\Validator;
use Core\Helper\ViewHelper;

class OrderController extends ViewHelper
{

    private $table = "jp_cart_global";
    private $tableCartDetail = "jp_cart_detail_global";
    private $tableStatus = "jp_cart_status_global";
    private $tableDomain = "jp_domain";
    private $tableMember = "jp_member";

    public function index()
    {

        $db = new DBHandler($this->table);
        $dbStatus = new DBHandler($this->tableStatus);
        $dbDomain = new DBHandler($this->tableDomain);
        // ✅ **Lấy tham số từ URL**
        $search = $_GET['search'] ?? ''; // Tìm kiếm theo tên
        $sort = $_GET['sort'] ?? 'desc'; // Sắp xếp (asc/desc)
        $data = array();

        $params = $this->getParams();

        // ✅ **Xây dựng điều kiện truy vấn**
        $conditions = [];
        if (!empty($search)) {
            $conditions['code'] = ['LIKE', "%$search%"];
        }


        // ✅ **Tùy chọn sắp xếp**
        $pages = 1;
        if (!empty($params["page"])) {
            $pages = $params["page"];
        }
        $limit = 10; // Số lượng domain trên mỗi trang
        $offset = ((int)$pages - 1) * $limit;

        $options = [
            'order_by' => ["id $sort"],
            'limit' => $limit,
            'offset' => $offset,
            'columns' => 'jp_cart_global.*, jp_domain.domain AS domain, jp_member.username AS username', // Định danh id cụ thể
            'joins' => [
                ['LEFT JOIN', 'jp_domain', 'jp_domain.id = jp_cart_global.id_domain'], // JOIN bảng jp_domain
                ['LEFT JOIN', 'jp_member', 'jp_member.id = jp_cart_global.id_member'] // JOIN bảng jp_member
            ]
        ];

        // ✅ **Truy vấn danh sách thành viên với các cột cần thiết**
        $data["cartGlobal"] = $db->getList($conditions, $options);

        // ✅ **Lấy danh sách trạng thái từ jp_cart_status_global**
        $cartStatusList = $dbStatus->getList([], [
            'columns' => 'id, name'
        ]);

        // Chuyển `id` thành key
        $cartStatusList = array_combine(array_column($cartStatusList, 'id'), $cartStatusList);

        foreach ($data["cartGlobal"] as $key => $val) {
            if (isset($cartStatusList[$val['id_status']])) {
                $data["cartGlobal"][$key]['status'] = $cartStatusList[$val['id_status']]['name'];
            }
        }

        $data["pagination"] = $this->renderPagination($db->countRows($conditions));
        //         $view->is_ajax = true;
        return $this->getLayout($data);
    }

    public function edit()
    {
        $db = new DBHandler($this->table);
        $dbCartDetail = new DBHandler($this->tableCartDetail);
        $dbStatus = new DBHandler($this->tableStatus);
        $dbDomain = new DBHandler($this->tableDomain);
        $dbMember = new DBHandler($this->tableMember);
        $view = new ViewHelper();
        $params = $this->getParams();
        $cartId = $params['id'];

        // ✅ Lấy thông tin giỏ hàng
        $cartGlobal = $db->getOne(['id' => $cartId]);

        if (!$cartGlobal) {
            return $view->getLayout(['error' => "Đơn hàng không tồn tại"]);
        }

        // // ✅ Lấy danh sách domain liên quan
        $data['infoDomain'] = $dbDomain->getOne(['id' => $cartGlobal['id_domain']]);
        $data['infoMember'] = $dbMember->getOne(['id' => $cartGlobal['id_member']]);

        // ✅ Lấy danh sách chi tiết đơn hàng
        $data['listCartDetail'] = $dbCartDetail->getList(['id_cart_global' => $cartId], [
            'columns' => 'id, id_cart_global, name, qty, price, last_payment_date, next_renewal_date, id_payment'
        ]);

        // ✅ Lấy danh sách trạng thái từ `jp_cart_status_global`
        $data['cartStatusList'] = $dbStatus->getList([], [
            'columns' => 'id, name'
        ]);

        // ✅ Gán giỏ hàng vào `$data`
        $data['cartGlobal'] = $cartGlobal;

        // ✅ Nếu form được submit (POST request)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new Validator();
            $validationRules = [
                'id_status' => ['required', 'in:0,1,2'],
            ];
            // ✅ Kiểm tra nếu có `mobile` thì mới thêm rule
            if (!empty($_POST['mobile'])) {
                $validationRules['mobile'] = ['nullable', 'regex:/^[0-9]{10,11}$/'];
            }
            $validator->validate($_POST, $validationRules);

            // Kiểm tra nếu có lỗi
            if ($validator->fails()) {
                $data['errors'] = $validator->errors(); // ✅ Truyền danh sách lỗi ra view
                $data['old'] = $_POST; // ✅ Giữ lại dữ liệu đã nhập
                return $view->getLayout($data);
            }

            // ✅ Lấy dữ liệu từ form để cập nhật
            $fields = [
                'name',
                'mobile',
                'address',
                'id_status',
                'email',
                'next_renewal_date',
                'tax_number',
                'total_price'
            ];

            $updateData = [];
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    $updateData[$field] = $_POST[$field];
                }
            }

            // ✅ Cập nhật dữ liệu vào database
            if (!empty($updateData)) {
                $success = $db->update($updateData, ['id' => $cartId]);
                if ($success) {
                    $data['cartGlobal'] = array_merge($cartGlobal, $updateData); // ✅ Cập nhật lại dữ liệu mới
                    $data['success'] = "Cập nhật giỏ hàng thành công";
                } else {
                    $data['error'] = "Không có dữ liệu nào được cập nhật";
                }
            }
        }

        // ✅ Trả về View với đầy đủ `$data`
        return $view->getLayout($data);
    }

    public function export()
    {
        // ✅ Kết nối Database
        $db = new DBHandler($this->table);
        $dbStatus = new DBHandler($this->tableStatus);
        $dbDomain = new DBHandler($this->tableDomain);

        // ✅ Lấy tham số từ URL
        $search = $_GET['search'] ?? ''; // Tìm kiếm theo tên
        $sort = $_GET['sort'] ?? 'desc'; // Sắp xếp (asc/desc)
        $filter = $_GET['filter'] ?? '';
        // ✅ Xây dựng điều kiện truy vấn
        $conditions = [];
        if (!empty($search)) {
            $conditions['code'] = ['LIKE', "%$search%"];
        }

        $options = [
            'order_by' => ["id $sort"],
            'columns' => 'jp_cart_global.*, jp_domain.domain AS domain, jp_member.username AS username, jp_member.fullname AS fullname',
            'joins' => [
                ['LEFT JOIN', 'jp_domain', 'jp_domain.id = jp_cart_global.id_domain'],
                ['LEFT JOIN', 'jp_member', 'jp_member.id = jp_cart_global.id_member']
            ]
        ];

        $cartGlobal = $db->getList($conditions, $options);

        // ✅ Header để trình duyệt nhận diện là file Excel `.xlsx`
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=DanhSach_DonHang.xlsx");
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
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid black; padding: 8px; text-align: left; }
                th { background-color: #D9EAD3; font-weight: bold; font-size: 14px; }
              </style>";
        echo "</head>";
        echo "<body>";
        echo "<table>";

        // ✅ Xuất tiêu đề cột
        echo "<tr>";
        echo "<th>STT</th>";
        echo "<th>Mã đơn hàng</th>";
        echo "<th>Tên tài khoản</th>";
        echo "<th>Username</th>";
        echo "<th>Tên website</th>";
        echo "<th>Tổng tiền</th>";
        echo "</tr>";

        // ✅ Xuất dữ liệu
        $stt = 1;
        foreach ($cartGlobal as $row) {
            // $totalPriceFormatted = number_format($row['total_price'], 0, ',', '.') . " đ";
            echo "<tr>";
            echo "<td>{$stt}</td>";
            echo "<td>{$row['code']}</td>";
            echo "<td>{$row['fullname']}</td>";
            echo "<td>{$row['username']}</td>";
            echo "<td>{$row['domain']}</td>";
            echo "<td style='text-align: right;'>{$row['total_price']}</td>";
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
