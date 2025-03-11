<style>
    .apps-market__image {
        width: 100px;
        height: 100px;
    }

    a {
        text-decoration: none;
        color: #000;
    }
</style>
<h2>Danh sách tài khoản</h2>

<div class="d-flex justify-content-between mb-4">
    <div>
        <form method="GET" action="/admin/payment-shipping/index" class="d-flex">
            <input type="text" class="form-control me-2" placeholder="Tìm kiếm..." name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <select name="filter" class="form-select me-2" style="width: 150px;">
                <option value="">Tất cả</option>
                <option value="4" <?= (($_GET['filter'] ?? '') == '4') ? 'selected' : '' ?>>Đi bộ</option>
                <option value="1" <?= (($_GET['filter'] ?? '') == '1') ? 'selected' : '' ?>>GHTK</option>
            </select>
            <button type="submit" class="btn btn-primary">Tìm</button>
        </form>
    </div>
    <a id="exportLink" href="/admin/payment-shipping/export" class="btn btn-success">Xuất file</a>
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>STT</th>
                <th>Mã thanh toán</th>
                <th>Mã đơn hàng đăng đơn</th>
                <th>Loại</th>
                <th>Tổng tiền</th>
                <th>Đăng đơn</th>
                <th>Website</th>
                <th>Tên khách hàng</th>
                <th>Tài khoản</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($listPaymentShipping)): ?>
                <?php $counter = 1; // Biến đếm ID tự động 
                ?>
                <?php foreach ($listPaymentShipping as $val): ?>
                    <tr>
                        <td><?= $counter++ ?></td>
                        <td><a href="/admin/payment-shipping/edit/id/<?= $val['id'] ?>"><?= htmlspecialchars($val['code_shipping'] ?? '', ENT_QUOTES, 'UTF-8') ?></a></td>
                        <td><?= htmlspecialchars($val['code_cart'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($val['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= number_format($val['total'], 0, ',', '.') ?> đ</td>
                        <td>
                            <?php
                            $typeLabels = [
                                0 => 'Chưa đăng đơn',
                                1 => 'Đã đăng đơn',
                            ];
                            echo htmlspecialchars($typeLabels[$val['order_status']] ?? 'N/A', ENT_QUOTES, 'UTF-8');
                            ?>
                        </td>
                        <td><?= htmlspecialchars($val['domain'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($val['fullname'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($val['username'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="/admin/payment-shipping/edit/id/<?= $val['id'] ?>" class="btn btn-sm btn-warning">Xem chi tiết</a>
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

    function updateExportLink() {
        const searchParam = new URLSearchParams(window.location.search);
        let exportUrl = "/admin/payment-shipping/export";

        if (searchParam.toString()) {
            exportUrl += "?" + searchParam.toString();
        }

        document.getElementById("exportLink").setAttribute("href", exportUrl);
    }

    // ✅ Cập nhật đường dẫn ngay khi trang load để giữ đúng tham số hiện tại
    document.addEventListener("DOMContentLoaded", updateExportLink);
</script>