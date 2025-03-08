<h2>Danh sách tài khoản</h2>

<div class="d-flex justify-content-between mb-4">
    <div>
        <form method="GET" action="/admin/member/index" class="d-flex">
            <input type="text" class="form-control me-2" placeholder="Tìm kiếm..." name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <select name="sort" class="form-select me-2" style="width: 150px;">
                <option value="desc" <?= (($_GET['sort'] ?? 'desc') == 'desc') ? 'selected' : '' ?>>Mới nhất</option>
                <option value="asc" <?= (($_GET['sort'] ?? '') == 'asc') ? 'selected' : '' ?>>Cũ nhất</option>
            </select>
            <button type="submit" class="btn btn-primary">Tìm</button>
        </form>
    </div>
    <!-- <a href="/admin/member/add" class="btn btn-success">Thêm mới</a> -->
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
            <?php if (!empty($member)): ?>
                <?php foreach ($member as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <?php if (!empty($user['image'])): ?>
                                <img src="<?= $this->getImageUrl($user['image']) ?>" alt="Avatar" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                            <?php else: ?>
                                <img src="<?= URL_ASSETS ?>/images/default.jpg" alt="Default Avatar" class="rounded-circle" width="40" height="40">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($user['fullname'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($user['mobile'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($user['updated'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <!-- Form-switch trạng thái ẩn/hiện -->
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-status" type="checkbox"
                                    id="switch_<?= $user['id'] ?>"
                                    data-id="<?= $user['id'] ?>"
                                    <?= !empty($user['showview']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="switch_<?= $user['id'] ?>">
                                    <?= !empty($user['showview']) ? 'Hiện' : 'Ẩn' ?>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="/admin/member/change_password/id/<?= $user['id'] ?>" class="btn btn-sm btn-success me-1">Đổi password</a>
                                <a href="/admin/member/edit/id/<?= $user['id'] ?>" class="btn btn-sm btn-primary me-1">Sửa</a>
                                <!-- <button type="button" class="btn btn-sm btn-danger delete-user" data-id="<?= $user['id'] ?>">Xóa</button> -->
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

<!-- JavaScript cho chức năng xóa -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".toggle-status").forEach(function(toggle) {
            toggle.addEventListener("change", function() {
                let userId = this.getAttribute("data-id");
                let newStatus = this.checked ? 1 : 0;
                let confirmMessage = newStatus ? "Bạn có chắc chắn muốn hiển thị người dùng này?" : "Bạn có chắc chắn muốn ẩn người dùng này?";

                // ✅ Hiển thị hộp thoại xác nhận trước khi thay đổi trạng thái
                if (!confirm(confirmMessage)) {
                    this.checked = !this.checked; // Hoàn tác nếu người dùng chọn "Hủy"
                    return;
                }

                // ✅ Gửi yêu cầu AJAX nếu người dùng xác nhận
                fetch('/admin/member/toggleStatus', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: userId,
                            showview: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.nextElementSibling.textContent = newStatus ? "Hiện" : "Ẩn";
                        } else {
                            alert("Lỗi khi cập nhật trạng thái!");
                            this.checked = !this.checked; // Hoàn tác nếu có lỗi
                        }
                    })
                    .catch(error => {
                        console.error("Lỗi:", error);
                        alert("Không thể cập nhật trạng thái.");
                        this.checked = !this.checked; // Hoàn tác nếu có lỗi
                    });
            });
        });
    });
</script>