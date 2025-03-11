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
<?php if (!empty($success)) : ?>
    <div class="alert alert-success"><?= $success; ?></div>
<?php endif; ?>
<div class="edit-cart-container">
    <h3>Thêm mới</h3>
    <hr>

    <form action="" method="post">
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-save" style="width: max-content;">Lưu lại</button>
        </div>

        <div class="row">
            <div class="mb-3 col-6 mx-auto">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Tên tiện ích</label>
                        <input type="text" class="form-control apps__market__name" name="name" onkeyup="getInputValueAndConvert(this, `#apps__market__permission`)" value="<?php echo !empty($old['name']) ? $old['name'] : ''; ?>">
                        <?php if (!empty($errors['name'])): ?>
                            <small class="error text-danger"><?= htmlspecialchars(implode(', ', $errors['name'])) ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="col-6 mb-3">
                        <label class="form-label">Tên phân quyền (không dấu)</label>
                        <input type="text" readonly class="form-control" id="apps__market__permission" name="app_permission_name">
                    </div>
                    <div class=" col-6 mb-3">
                        <label class="form-label">Giá bán</label>
                        <input type="number" class="form-control apps__market__price" name="price" value="<?php echo !empty($old['price']) ? $old['price'] : ''; ?>">
                        <?php if (!empty($errors['price'])): ?>
                            <small class="error text-danger"><?= htmlspecialchars(implode(', ', $errors['price'])) ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Loại</label>
                        <select class="form-select platform__select-custom" aria-label="Default select example" name="type">
                            <option value="0" selected>Nền tảng</option>
                            <option value="1">Tiện ích</option>
                            <option value="2">Nâng cấp</option>
                        </select>
                        <?php if (!empty($errors['type'])): ?>
                            <small class="error text-danger"><?= htmlspecialchars(implode(', ', $errors['type'])) ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">URL liên kết</label>
                        <input type="text" class="form-control " name="url" value="<?php echo !empty($old['url']) ? $old['url'] : ''; ?>">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Hình ảnh</label>
                        <input type="file" class="form-control " name="images">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Template</label>
                        <input type="text" class="form-control template__name" onkeyup="getInputValueAndConvert(this, `#template__name-render`)">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Rendered Template Files (không dấu)</label>
                        <input type="text" class="form-control" readonly id="template__name-render" name="template" value="<?php echo !empty($old['template']) ? $old['template'] : ''; ?>">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea type="text" class="form-control apps__market__des" name="description"><?php echo !empty($old['description']) ? $old['description'] : ''; ?></textarea>
                        <?php if (!empty($errors['description'])): ?>
                            <small class="error text-danger"><?= htmlspecialchars(implode(', ', $errors['description'])) ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Danh sách page sử dụng quyền này</label>
                        <textarea type="text" class="form-control apps__market__des" name="data_default"></textarea>
                    </div>
                </div>

            </div>
        </div>

    </form>
</div>
<script>
    function getInputValueAndConvert(inputElement, elmSet) {
        const nameValue = inputElement.value.trim();
        let marketPermission = document.querySelector(elmSet);

        if (!nameValue) {
            alert("Vui lòng nhập tên tiện ích!");
            return;
        }

        const formData = new FormData();
        formData.append('name_convert', nameValue);

        fetch('/admin/apps-market/convertSlug', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                marketPermission.value = data.slug;
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert("Đã xảy ra lỗi khi gửi yêu cầu!");
            });
    }
</script>