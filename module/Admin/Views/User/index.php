<?php
// Kiểm tra và đảm bảo biến tồn tại
$users = $users ?? [];
$success = $_SESSION['success_message'] ?? '';

// Xóa thông báo khỏi session sau khi hiển thị
if (isset($_SESSION['success_message'])) {
    unset($_SESSION['success_message']);
}
?>

<div class="container-fluid">
    <h2>Quản Lý Người Dùng</h2>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <a href="/admin/user/create" class="btn btn-primary">Thêm Người Dùng Mới</a>
        </div>
        
        <div class="card-body">
            <?php if (!empty($users)): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Tên Đăng Nhập</th>
                            <th>Họ Tên</th>
                            <th>Email</th>
                            <th>Điện Thoại</th>
                            <th>Vai Trò</th>
                            <th>Trạng Thái</th>
                            <th>Ngày Tạo</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user):?>
                            <tr>
                                <td><?= htmlspecialchars($user['id'] ?? 'N/A') ?></td>
                                <td>
                                    <img src="<?= $this->getImageUrl($user['avatar'] ?? '', '50x50') ?>" 
                                         alt="Avatar" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td><?= htmlspecialchars($user['username'] ?? 'Chưa có') ?></td>
                                <td><?= htmlspecialchars($user['fullname'] ?? 'Chưa có') ?></td>
                                <td><?= htmlspecialchars($user['email'] ?? 'Chưa có') ?></td>
                                <td><?= htmlspecialchars($user['mobile'] ?? 'N/A') ?></td>
                                <td>
                                    <?php 
                                    $role = $user['role'] ?? 'user'; // Mặc định là 'user' nếu không có
                                    $roleClass = ($role === 'admin') ? 'text-danger' : 'text-primary';
                                    $roleText = ($role === 'admin') ? 'Quản Trị Viên' : 'Người Dùng';
                                    ?>
                                    <span class="<?= $roleClass ?>"><?= $roleText ?></span>
                                </td>
                                <td>
                                    <?php 
                                    $online = $user['online'] ?? 0; // Mặc định là Offline nếu không có
                                    $statusClass = $online ? 'text-success' : 'text-secondary';
                                    $statusText = $online ? 'Online' : 'Offline';
                                    ?>
                                    <span class="<?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                                <td>
                                    <?= isset($user['datecreate']) ? date('d/m/Y', strtotime($user['datecreate'])) : 'Chưa cập nhật' ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/admin/user/edit/id/<?= htmlspecialchars($user['id'] ?? '') ?>" class="btn btn-sm btn-warning">Sửa</a>
                                        <a href="/admin/user/delete/id/<?= htmlspecialchars($user['id'] ?? '') ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">Không có người dùng nào.</p>
            <?php endif; ?>
        </div>

        <?php if (!empty($pagination)): ?>
        <div class="card-footer">
            <?= $pagination ?>
        </div>
        <?php endif; ?>
    </div>
</div>
