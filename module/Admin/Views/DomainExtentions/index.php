<style>
    a {
        text-decoration: none;
        color: #000;
    }
</style>
<h2>Danh sách tài khoản</h2>

<div class="d-flex justify-content-between mb-4">
    <div>
        <form method="GET" action="/admin/domain-extentions/index" class="d-flex">
            <input type="text" class="form-control me-2" placeholder="Tìm kiếm..." name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <select name="filter" class="form-select me-2" style="width: 150px;">
                <option value="">Tất cả</option>
                <option value="1" <?= (($_GET['filter'] ?? '') == '1') ? 'selected' : '' ?>>Việt Nam</option>
                <option value="2" <?= (($_GET['filter'] ?? '') == '2') ? 'selected' : '' ?>>Quốc tế</option>
            </select>
            <button type="submit" class="btn btn-primary">Tìm</button>
        </form>
    </div>
    <a href="/admin/domain-extentions/add" class="btn btn-success">Thêm mới</a>
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên domain mở rộng</th>
                <th>Giá bán</th>
                <th>Giá khuyến mãi</th>
                <th>Phần trăm giảm</th>
                <th>Ưu tiên</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($domainEx)): ?>
                <?php $counter = 1; // Biến đếm ID tự động 
                ?>
                <?php foreach ($domainEx as $val): ?>
                    <tr>
                        <td><?= $counter++ ?></td>
                        <td><a href="/admin/domain-extentions/edit/id/<?= $val['id'] ?>"><?= htmlspecialchars($val['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></a> </td>
                        <td><?= htmlspecialchars($val['price'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($val['price_km'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($val['percent'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <!-- Form-switch trạng thái ẩn/hiện -->
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-priority" type="checkbox"
                                    id="switch_<?= $val['id'] ?>"
                                    data-id="<?= $val['id'] ?>"
                                    <?= !empty($val['priority']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="switch_<?= $val['id'] ?>">
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="/admin/domain-extentions/edit/id/<?= $val['id'] ?>" class="btn btn-sm btn-warning">Xem chi tiết</a>
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
        document.querySelectorAll(".toggle-priority").forEach(function(toggle) {
            toggle.addEventListener("change", function() {
                let idElm = this.getAttribute("data-id");
                let newStatus = this.checked ? 1 : 0;
                let confirmMessage = newStatus ? "Bạn có chắc chắn muốn ưu tiên domain dùng này?" : "Bạn có chắc chắn muốn bỏ ưu tiên cho domain này?";

                // ✅ Hiển thị hộp thoại xác nhận trước khi thay đổi trạng thái
                if (!confirm(confirmMessage)) {
                    this.checked = !this.checked; // Hoàn tác nếu người dùng chọn "Hủy"
                    return;
                }

                // ✅ Gửi yêu cầu AJAX nếu người dùng xác nhận
                fetch('/admin/domain-extentions/togglePriority', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: idElm,
                            priority: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
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