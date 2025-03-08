<h2>Danh sách tài khoản</h2>

<div class="d-flex justify-content-between mb-4">
    <div>
        <form method="GET" action="/admin/user/index" class="d-flex">
            <input type="text" class="form-control me-2" placeholder="Tìm kiếm..." name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <select name="sort" class="form-select me-2" style="width: 150px;">
                <option value="desc" <?= (($_GET['sort'] ?? 'desc') == 'desc') ? 'selected' : '' ?>>Mới nhất</option>
                <option value="asc" <?= (($_GET['sort'] ?? '') == 'asc') ? 'selected' : '' ?>>Cũ nhất</option>
            </select>
            <button type="submit" class="btn btn-primary">Tìm</button>
        </form>
    </div>
    <a href="/admin/user/add" class="btn btn-success">Thêm mới</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Avatar</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Điện thoại</th>
                <th>Ngày tạo</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <?php if (!empty($user['avatar'])): ?>
                                <img src="<?= $this->getImageUrl($user['avatar']) ?>" alt="Avatar" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                            <?php else: ?>
                                <img src="<?= URL_ASSETS ?>/images/default.jpg" alt="Default Avatar" class="rounded-circle" width="40" height="40">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($user['fullname'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($user['mobile'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($user['datecreate'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <?php if (!empty($user['online'])): ?>
                                <span class="badge bg-success">Online</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Offline</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="/admin/user/edit/id/<?= $user['id'] ?>" class="btn btn-sm btn-primary me-1">Sửa</a>
                                <button type="button" class="btn btn-sm btn-danger delete-user" data-id="<?= $user['id'] ?>">Xóa</button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Không có dữ liệu</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Phân trang -->
<?php echo $pagination; ?>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa user này?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Xóa</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript cho chức năng xóa -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-user');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        let userIdToDelete = null;

        // Thêm sự kiện click cho các nút xóa
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                userIdToDelete = this.getAttribute('data-id');
                deleteModal.show();
            });
        });

        // Xử lý khi nhấn nút xác nhận xóa trong modal
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (!userIdToDelete) return;

            // Tạo FormData để gửi request
            const formData = new FormData();
            formData.append('id', userIdToDelete);

            // Gửi request xóa bằng fetch API
            fetch('/admin/user/delete', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    deleteModal.hide();

                    if (data.status === 'success') {
                        // Hiển thị thông báo thành công
                        alert(data.message);

                        // Tải lại trang để cập nhật danh sách
                        window.location.reload();
                    } else {
                        // Hiển thị thông báo lỗi
                        alert(data.message || 'Có lỗi xảy ra khi xóa user');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    deleteModal.hide();
                    alert('Đã xảy ra lỗi khi xóa user');
                });
        });
    });
</script>