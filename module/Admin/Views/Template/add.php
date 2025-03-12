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
    <h3>Thêm mới</h3>
    <hr>

    <form action="" method="post">
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-save" style="width: max-content;">Lưu thay đổi</button>
        </div>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success my-3" style="width: max-content;"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <div class="mb-3 row">
            <div class="col-6 mb-3">
                <label class="form-label">Loại template</label>
                <select id="menu_block" name="type" class="form-control input-sm"
                    style="height: auto; ">
                    <?php
                    foreach ($listTypeTemplate as $val) {
                        echo "<option value='" . $val['id'] . "'>" . $val['name'] . "</option>";
                    }
                    ?>
                </select>
                <?php if (!empty($errors['type'])): ?>
                    <small class="error text-danger"><?= htmlspecialchars(implode(', ', $errors['type'])) ?></small>
                <?php endif; ?>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">Tên template</label>
                <input type="text" class="form-control template_name" name="name">
                <?php if (!empty($errors['name'])): ?>
                    <small class="error text-danger"><?= htmlspecialchars(implode(', ', $errors['name'])) ?></small>
                <?php endif; ?>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">Hình</label>
                <input type="file" class="form-control template_name" name="images">
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">Tên DB template</label>
                <input type="text" class="form-control template_name" name="name_db_template">
                <?php if (!empty($errors['name_db_template'])): ?>
                    <small class="error text-danger"><?= htmlspecialchars(implode(', ', $errors['name_db_template'])) ?></small>
                <?php endif; ?>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">Link website template</label>
                <input type="text" class="form-control template_name" name="url_page_home_template">
                <?php if (!empty($errors['url_page_home_template'])): ?>
                    <small class="error text-danger"><?= htmlspecialchars(implode(', ', $errors['url_page_home_template'])) ?></small>
                <?php endif; ?>
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