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
    <h3>Chỉnh Sửa</h3>
    <hr>

    <form action="" method="post">
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-save" style="width: max-content;">Lưu thay đổi</button>
        </div>

        <div class="row">
            <div class="mb-3 col-6 mx-auto">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Tên tiện ích</label>
                        <input type="text" class="form-control apps__market__name" name="name" value="<?php echo $appsMarketDetail['name']; ?>">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Tên phân quyền (không dấu)</label>
                        <input type="text" readonly class="form-control apps__market__name" value="<?php echo $appsMarketDetail['app_permission_name']; ?>" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Giá bán</label>
                        <input type="number" class="form-control apps__market__price" name="price" value="<?php echo $appsMarketDetail['price']; ?>">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Loại</label>
                        <select class="form-select platform__select-custom" aria-label="Default select example" name="type">
                            <option value="0" <?php echo $appsMarketDetail['type'] == 0 ? 'selected' : ''; ?>>Nền tảng</option>
                            <option value="1" <?php echo $appsMarketDetail['type'] == 1 ? 'selected' : ''; ?>>Tiện ích</option>
                            <option value="2" <?php echo $appsMarketDetail['type'] == 2 ? 'selected' : ''; ?>>Nâng cấp</option>
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">URL liên kết</label>
                        <input type="text" class="form-control " name="url" value="<?php echo $appsMarketDetail['url']; ?>">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Hình ảnh</label>
                        <input type="file" class="form-control " name="images">
                        <input type="hidden" class="form-control " name="images" value="<?php echo $appsMarketDetail['images']; ?>">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Rendered Template Files (không dấu)</label>
                        <input type="text" class="form-control template__name-render" readonly value="<?php echo $appsMarketDetail['template']; ?>">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea type="text" class="form-control apps__market__des" name="description"><?php echo $appsMarketDetail['description']; ?></textarea>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Danh sách page sử dụng quyền này</label>
                        <textarea type="text" class="form-control apps__market__des" name="data_default"><?php echo $appsMarketDetail['data_default']; ?></textarea>
                    </div>
                </div>

            </div>
        </div>

    </form>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        function toggleInputs() {
            const percentRadio = document.getElementById('percent1');
            const priceRadio = document.getElementById('priceKM2');

            const percentInput = document.getElementById('percent_input');
            const priceInput = document.getElementById('price_km_input');

            if (percentRadio.checked) {
                percentInput.disabled = false;
                priceInput.disabled = true;
            } else if (priceRadio.checked) {
                percentInput.disabled = true;
                priceInput.disabled = false;
            }
        }

        // Gán sự kiện khi thay đổi radio
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', toggleInputs);
        });

        // Thiết lập trạng thái ban đầu
        toggleInputs();

        let priorityCheckbox = document.getElementById("priority");

        // Khi checkbox thay đổi trạng thái
        priorityCheckbox.addEventListener("change", function() {
            this.value = this.checked ? 1 : 0;
        });
    });
</script>