<?php

namespace module\Admin\Controllers;

use Core\Database\DBHandler; // ✅ Load module database

use Core\Helper\ImageUploader;
use Core\Helper\ViewHelper;
use Core\Helper\Validator;

class MemberController extends ViewHelper
{

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
    public function index()
    {

        $db = new DBHandler($this->table);

        // ✅ **Lấy tham số từ URL**
        $search = $_GET['search'] ?? ''; // Tìm kiếm theo tên
        $sort = $_GET['sort'] ?? 'desc'; // Sắp xếp (asc/desc)
        $data = array();

        $params = $this->getParams();

        // ✅ **Xây dựng điều kiện truy vấn**
        $conditions = [];
        if (!empty($search)) {
            $conditions['username'] = ['LIKE', "%{$search}%"];
        }

        // ✅ **Tùy chọn sắp xếp**
        $pages = 1;
        if (!empty($params["page"])) {
            $pages = $params["page"];
        }
        $limit = 10; // Số lượng người dùng trên mỗi trang
        $offset = ((int)$pages - 1) * $limit;

        $options = [
            'order_by' => ["datecreate $sort"],
            'limit' => $limit,
            'offset' => $offset,
            'columns' => 'id, fullname, username, email, mobile, showview, image',
        ];
        // ✅ **Truy vấn danh sách thành viên với các cột cần thiết**
        $data["member"] = $db->getList($conditions, $options);


        $data["pagination"] = $this->renderPagination($db->countRows($conditions));
        //         $view->is_ajax = true;
        return $this->getLayout($data);
    }

    /**
     * Xóa user
     */
    public function delete()
    {
        // Chỉ chấp nhận POST request để tránh CSRF
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 403 Forbidden');
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            exit;
        }

        $memberId = $_POST['id'] ?? 0;

        // Không cho phép xóa chính mình
        if ($memberId == $_SESSION['user_id']) {
            echo json_encode(['status' => 'error', 'message' => 'Không thể xóa tài khoản đang đăng nhập']);
            exit;
        }

        $db = new DBHandler($this->table);

        // Lấy thông tin user để xóa avatar nếu có
        $member = $db->getOne(['id' => $memberId]);


        if ($member && $db->delete(['id' => $memberId])) {
            // Xóa avatar nếu có
            // if (!empty($member['image'])) {
            //     ImageUploader::removeImage($member['image']);
            // }

            echo json_encode(['status' => 'success', 'message' => 'Xóa user thành công']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra khi xóa user']);
        }
        exit;
    }

    /**
     * Hiển thị form thêm mới user và xử lý thêm user
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
                'username' => ['required', 'min:3'],
                'password' => ['required', 'min:6'],
                'fullname' => ['required'],
                'email' => ['required', 'email']
            ]);

            if ($validator->fails()) {
                $data['errors'] = $validator->errors();
                $data['old'] = $_POST; // Giữ lại dữ liệu đã nhập
            } else {
                // Xử lý avatar nếu có
                if (!empty($_FILES['avatar']['name'])) {
                    $upload = new ImageUploader();
                    $avatarName = $upload->upload($_FILES['avatar'], array("50x50", "100x100"));
                    if ($avatarName) {
                        $_POST['avatar'] = $avatarName;
                    }
                }

                // Mã hóa mật khẩu với password_hash (an toàn hơn md5)
                $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $_POST['token'] = bin2hex(random_bytes(16)); // Token ngẫu nhiên
                $_POST['datecreate'] = date('Y-m-d H:i:s');

                // Bỏ trường confirm_password nếu có
                if (isset($_POST['confirm_password'])) {
                    unset($_POST['confirm_password']);
                }

                // Bỏ trường avatar_file nếu có
                if (isset($_POST['avatar_file'])) {
                    unset($_POST['avatar_file']);
                }

                // Bỏ trường cropped_image nếu có
                if (isset($_POST['cropped_image'])) {
                    unset($_POST['cropped_image']);
                }

                // Insert trực tiếp dữ liệu POST vào database
                if ($db->insert($_POST)) {
                    $data['success'] = "Thêm mới user thành công!";
                } else {
                    $data['error'] = "Có lỗi xảy ra khi thêm mới user. Vui lòng thử lại.";
                    $data['old'] = $_POST; // Giữ lại dữ liệu đã nhập
                }
            }
        }

        return $view->getLayout($data);
    }

    public function edit()
    {
        $db = new DBHandler($this->table);
        $view = new ViewHelper();
        $params = $this->getParams();

        $memberId = $params['id'];
        // Lấy thông tin người dùng từ database
        $member = $db->getOne(['id' => $memberId]);
        if (!$member) {
            return $view->getLayout(['error' => "Người dùng không tồn tại"]);
        }

        // ✅ Nếu form được submit (POST request)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new Validator();
            $validator->validate($_POST, [
                'fullname' => ['required', 'min:7'],
                'email' => ['required', 'email'],
                'mobile' => ['nullable', 'regex:/^[0-9]{10,11}$/'],
                'birthday' => ['nullable', 'date', 'before_or_equal:today'],
                'sex' => ['required', 'in:0,1'],
            ]);

            // Kiểm tra nếu có lỗi
            if ($validator->fails()) {
                $data['errors'] = $validator->errors(); // ✅ Truyền danh sách lỗi ra view
                $data['old'] = $_POST; // ✅ Giữ lại dữ liệu đã nhập
                return $view->getLayout(array_merge(['member' => $member], $data));
            }
            // Lấy dữ liệu hợp lệ từ form
            $updateData = $_POST;

            // Loại bỏ id khỏi dữ liệu cập nhật
            unset($updateData['id']);

            // Nếu ngày sinh rỗng, set NULL
            if (empty($updateData['birthday'])) {
                $updateData['birthday'] = null;
            }

            // ✅ Upload avatar nếu có
            if (!empty($_FILES['avatar']['name'])) {
                $uploadedAvatar = ImageUploader::upload($_FILES['avatar'], ["50x50", "100x100"]);
                if ($uploadedAvatar) {
                    $updateData['image'] = $uploadedAvatar;
                }
            }

            // Xử lý xóa avatar nếu người dùng chọn xóa
            if (!empty($_POST['remove_avatar']) && !empty($member['avatar'])) {
                ImageUploader::remove($member['avatar']);
                $updateData['image'] = '';
            }

            // Thêm trường `updated`
            $updateData['updated'] = date('Y-m-d H:i:s');

            // ✅ Cập nhật dữ liệu vào database
            if (!empty($updateData)) {
                $success = $db->update($updateData, ['id' => $memberId]);
                if ($success) {
                    $member = array_merge($member, $updateData);
                    header("Location: /admin/member/index");
                    exit();
                }
            }
            return $view->getLayout([
                'member' => $member,
                'error' => "Không có dữ liệu nào được cập nhật"
            ]);

            // return $view->getLayout([
            //     'member' => $member,
            //     'error' => "Không có dữ liệu nào được cập nhật"
            // ], ['layoutPath' => BASE_PATH . "/module/Admin/Views/Member/index.php"]);
        }

        // ✅ Nếu chỉ là GET request, hiển thị form chỉnh sửa
        return $view->getLayout(['member' => $member]);
    }


    /**
     * Hiển thị trang đổi mật khẩu và xử lý đổi mật khẩu khi submit
     */
    public function change_password()
    {
        $view = new ViewHelper();
        $data = array();
        $params = $this->getParams();

        $db = new DBHandler($this->table);
        $data['memberId'] = $memberId = $params['id'];


        // Nếu là phương thức POST, xử lý đổi mật khẩu
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // $oldPassword = $_POST['old_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($newPassword !== $confirmPassword) {
                $data['error'] = "Mật khẩu xác nhận không khớp.";
                return $view->getLayout($data);
            }

            $validator = new Validator();
            $validator->validate($_POST, [
                'new_password' => ['required', 'password'],
            ]);

            // Kiểm tra nếu có lỗi
            if ($validator->fails()) {
                $data['errors'] = $validator->errors(); // ✅ Truyền danh sách lỗi ra view
                $data['old'] = $_POST; // ✅ Giữ lại dữ liệu đã nhập
                return $view->getLayout($data);
            }

            // Kiểm tra user tồn tại
            $member = $db->getOne(['id' => $memberId]);


            if (!$member) {
                $data['error'] = "Người dùng không tồn tại.";
                return $view->getLayout($data);
            }

            // Cập nhật mật khẩu mới bằng `password_hash()`
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStatus = $db->update(['password' => $hashedNewPassword], ['id' => $memberId]);

            if ($updateStatus) {
                $data['success'] = "Đổi mật khẩu thành công.";
            } else {
                $data['error'] = "Có lỗi xảy ra khi đổi mật khẩu.";
            }
        }

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
}
