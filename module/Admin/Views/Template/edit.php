<style>
    .edit-cart-container {
        max-width: 100%;
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin: auto;
    }

    .edit-cart-container h3 {
        text-align: center;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-weight: bold;
        color: #333;
    }

    .table {
        margin-top: 20px;
    }

    .btn-save,
    .btn-save:hover {
        background-color: #007bff;
        border: none;
        color: white;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        width: 100%;
    }
</style>
<div class="edit-cart-container">
    <h3>Chỉnh Sửa</h3>
    <hr>
    <form action="" method="post">
        <div class="d-flex justify-content-end mt-4">
            <button type="button" class="btn btn-danger me-3" style="width: max-content;" onclick="confirmAndDelete(<?php echo $templateDetail['id']; ?>)">Xóa</button>
            <button type="submit" class="btn btn-save" style="width: max-content;">Lưu thay đổi</button>
        </div>

        <div class="mb-3 row">

            <div class="col-6 mb-3">
                <label class="form-label">Loại template</label>
                <select id="menu_block" name="type" class="form-control input-sm"
                    style="height: auto; ">
                    <?php
                    foreach ($listTypeTemplate as $val) {
                        $selected = '';
                        if ($template['type'] == $val['id']) {
                            $selected = 'selected';
                        }
                        echo "<option $selected value='" . $val['id'] . "'>" . $val['name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">Tên template</label>
                <input type="text" class="form-control template_name" name="name" value="<?php echo $templateDetail['name'] ?>">
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">Hình</label>
                <input type="file" class="form-control" name="images">
                <!-- <p style="display: none" id="image-name"> <?php echo $templateDetail["images"]; ?> </p> -->
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">Tên DB template</label>
                <input type="text" class="form-control template_name" name="name_db_template" value="<?php echo $templateDetail['name_db_template'] ?>">
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">Link website của template</label>
                <input type="text" class="form-control template_name" name="url_page_home_template" value="<?php echo $templateDetail['url_page_home_template'] ?>">
            </div>

        </div>

    </form>
</div>
<script>
    function confirmAndDelete(id) {
        // Hiển thị hộp thoại xác nhận
        if (!confirm("Bạn có chắc chắn muốn xóa mục này không?")) {
            return; // Nếu hủy, thoát khỏi hàm
        }

        // Tạo FormData để gửi request
        const formData = new FormData();
        formData.append('id', id);

        // Gửi request xóa bằng Fetch API
        fetch('/admin/template/delete', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message || "Xóa thành công!");
                    location.href = '/admin/template/index';
                } else {
                    alert(data.message || "Có lỗi xảy ra khi xóa!");
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert("Đã xảy ra lỗi khi gửi yêu cầu xóa!");
            });
    }
</script>