<style>
    a {
        text-decoration: none;
        color: #000;
    }
</style>
<h2>Danh sách tài khoản</h2>

<div class="d-flex justify-content-between mb-4">
    <div>
        <form method="GET" action="/admin/order/index" class="d-flex">
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
                <th>STT</th>
                <th>Mã đơn hàng</th>
                <th>Họ tên</th>
                <th>Điện thoại</th>
                <th>Địa chỉ</th>
                <th>Trạng thái</th>
                <th>Tổng tiền</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($cartGlobal)): ?>
                <?php $counter = 1; // Biến đếm ID tự động 
                ?>
                <?php foreach ($cartGlobal as $cart): ?>
                    <tr>
                        <td><?= $counter++ ?></td>
                        <td><a href="/admin/order/edit/id/<?= $cart['id'] ?>"><?= htmlspecialchars($cart['code'] ?? '', ENT_QUOTES, 'UTF-8') ?></a> </td>
                        <td><?= htmlspecialchars($cart['name'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($cart['mobile'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($cart['address'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <span class="badge <?= ($cart['id_status'] == 2) ? 'bg-success' : 'bg-warning' ?>">
                                <?= htmlspecialchars($cart['status'] ?? 'Không xác định', ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>
                        <td><?= number_format($cart['total_price'], 0, ',', '.') ?> đ</td>
                        <td>
                            <div class="btn-group">
                                <!-- <a href="/admin/order/view/id/<?= $cart['code'] ?>" class="btn btn-sm btn-primary">Xem</a> -->
                                <a href="/admin/order/edit/id/<?= $cart['id'] ?>" class="btn btn-sm btn-warning">Xem chi tiết</a>
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
            fetch('/admin/member/delete', {
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