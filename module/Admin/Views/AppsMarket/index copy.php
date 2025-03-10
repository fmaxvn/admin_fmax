<style>
    .apps-market__image {
        width: 100px;
        height: 100px;
    }
</style>
<h2>Danh sách tài khoản</h2>

<div class="d-flex justify-content-between mb-4">
    <div>
        <form method="GET" action="/admin/apps-market/index" class="d-flex">
            <input type="text" class="form-control me-2" placeholder="Tìm kiếm..." name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <select name="filter" class="form-select me-2" style="width: 150px;">
                <option value="">Tất cả</option>
                <option value="0" <?= (($_GET['filter'] ?? '') == '0') ? 'selected' : '' ?>>Nền tảng</option>
                <option value="1" <?= (($_GET['filter'] ?? '') == '1') ? 'selected' : '' ?>>Tiện ích</option>
            </select>
            <button type="submit" class="btn btn-primary">Tìm</button>
        </form>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>STT</th>
                <th>Hình ảnh</th>
                <th>Tên tiện ích</th>
                <th>Giá</th>
                <th>Loại</th>
                <th>Ẩn/hiện</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($listAppsMarket)): ?>
                <?php $counter = 1; // Biến đếm ID tự động 
                ?>
                <?php foreach ($listAppsMarket as $val): ?>
                    <tr>
                        <td><?= $counter++ ?></td>
                        <td><img class="apps-market__image" src="<?= $this->getImageUrl($val['images']) ?>" alt=""></td>
                        <td><?= htmlspecialchars($val['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($val['price'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <?php
                            $typeLabels = [
                                0 => 'Nền tảng',
                                1 => 'Tiện ích',
                                2 => 'Nâng cấp'
                            ];
                            echo htmlspecialchars($typeLabels[$val['type']] ?? 'N/A', ENT_QUOTES, 'UTF-8');
                            ?>
                        </td>

                        <td>
                            <!-- Form-switch trạng thái ẩn/hiện -->
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-priority" type="checkbox"
                                    id="switch_<?= $val['id'] ?>"
                                    data-id="<?= $val['id'] ?>"
                                    <?= !empty($val['showview']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="switch_<?= $val['id'] ?>">
                                </label>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($val['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>

                        <td>
                            <div class="btn-group">
                                <a href="/admin/apps-market/edit/id/<?= $val['id'] ?>" class="btn btn-sm btn-warning">Xem chi tiết</a>
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
                let confirmMessage = newStatus ? "Bạn có chắc chắn muốn hiện?" : "Bạn có chắc chắn muốn ẩn?";

                // ✅ Hiển thị hộp thoại xác nhận trước khi thay đổi trạng thái
                if (!confirm(confirmMessage)) {
                    this.checked = !this.checked; // Hoàn tác nếu người dùng chọn "Hủy"
                    return;
                }

                // ✅ Gửi yêu cầu AJAX nếu người dùng xác nhận
                fetch('/admin/apps-market/toggleStatus', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: idElm,
                            showview: newStatus
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