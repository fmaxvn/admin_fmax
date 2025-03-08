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

    public function index()
    {

        $db = new DBHandler($this->table);
        $dbStatus = new DBHandler($this->tableStatus);

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
            'columns' => 'id, code, name, mobile, address, id_status, total_price',
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
        $view = new ViewHelper();
        $params = $this->getParams();
        $cartId = $params['id'];
        // ✅ **Truy vấn danh sách thành viên với các cột cần thiết**
        $cartGlobal = $db->getOne(['id' => $cartId]);

        // Lấy thông tin list đơn hàng chi tiết từ database
        $listCartDetail = $dbCartDetail->getList(['id_cart_global' => $cartId], [
            'columns' => 'id, id_cart_global, name, qty, price, last_payment_date, next_renewal_date, id_payment'
        ]);

        // ✅ **Lấy danh sách trạng thái từ jp_cart_status_global**
        $cartStatusList = $dbStatus->getList([], [
            'columns' => 'id, name'
        ]);
        if (!$cartGlobal) {
            return $view->getLayout(['error' => "Đơn hàng không tồn tại"]);
        }
        // ✅ Nếu form được submit (POST request)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new Validator();
            $validator->validate($_POST, [
                'mobile' => ['nullable', 'regex:/^[0-9]{10,11}$/'],
                'id_status' => ['required', 'in:0,1,2'],
            ]);

            // Kiểm tra nếu có lỗi
            if ($validator->fails()) {
                $data['errors'] = $validator->errors(); // ✅ Truyền danh sách lỗi ra view
                $data['old'] = $_POST; // ✅ Giữ lại dữ liệu đã nhập
                // return $view->getLayout(array_merge(['member' => $member], $data));
            }

            // Lấy toàn bộ dữ liệu từ form
            $fields = [
                'name',
                'mobile',
                'address',
                'id_status',
                'email',
                'next_renewal_date',
                'tax_number',
                'total_price',
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
                    $cartGlobal = array_merge($cartGlobal, $updateData); // Cập nhật dữ liệu mới vào biến cartGlobal
                    return $view->getLayout([
                        'cartGlobal' => $cartGlobal,
                        'cartStatusList' => $cartStatusList,
                        'listCartDetail' => $listCartDetail,
                        'success' => "Cập nhật giỏ hàng thành công"
                    ]);
                }
            }

            return $view->getLayout([
                'cartGlobal' => $cartGlobal,
                'error' => "Không có dữ liệu nào được cập nhật"
            ]);
        }

        // ✅ Nếu chỉ là GET request, hiển thị form chỉnh sửa
        return $view->getLayout([
            'cartGlobal' => $cartGlobal,
            'cartStatusList' => $cartStatusList,
            'listCartDetail' => $listCartDetail,
        ]);
    }
}
