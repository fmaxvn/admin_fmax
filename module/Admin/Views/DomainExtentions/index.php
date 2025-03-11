<style>
    a {
        text-decoration: none;
        color: #000;
    }
</style>
<h2>Danh sách tài khoản</h2>

<div class="d-flex justify-content-end mb-4">
    <!-- <div>
        <form method="GET" action="/admin/domain-extentions/index" class="d-flex">
            <input type="text" class="form-control me-2" placeholder="Tìm kiếm..." name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <select name="filter" class="form-select me-2" style="width: 150px;">
                <option value="">Tất cả</option>
                <option value="1" <?= (($_GET['filter'] ?? '') == '1') ? 'selected' : '' ?>>Việt Nam</option>
                <option value="2" <?= (($_GET['filter'] ?? '') == '2') ? 'selected' : '' ?>>Quốc tế</option>
            </select>
            <button type="submit" class="btn btn-primary">Tìm</button>
        </form>
    </div> -->
    <a href="/admin/domain-extentions/add" class="btn btn-success ">Thêm mới</a>
</div>
<div class="container-fluid px-0">
    <!-- AG data table -->
    <div class="box-table dragon"
        style="width: auto; height: 100%; overflow-x: auto; cursor: grab; cursor : -o-grab; cursor : -moz-grab; cursor : -webkit-grab;">
        <?php include('components/table.phtml') ?>
    </div>
    <!--End AG data table -->
</div>

<!-- Phân trang -->
<?php //echo $pagination; 
?>


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