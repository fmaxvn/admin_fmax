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


    <!-- ✅ FORM CHỈNH SỬA ĐƠN HÀNG -->
    <form action="" method="post">
        <!-- ID đơn hàng (Ẩn) -->
        <!-- <input type="hidden" name="id" value="<?= htmlspecialchars($cartGlobal['id'] ?? '') ?>"> -->
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-save" style="width: max-content;">Lưu thay đổi</button>
        </div>

        <div class="row">
            <div class="mb-3 col-6 mx-auto">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Tên tên domain</label>
                        <input type="text" class="form-control domain__name" name="name" value="<?php echo $domainExtentions['name']; ?>">
                        <?php if (!empty($errors['name'])): ?>
                            <small class="error text-danger"><?= htmlspecialchars(implode(', ', $errors['name'])) ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label" for="priority">Ưu tiên</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="priority" id="priority" value="<?php echo !empty($domainExtentions['priority']) ? 1 : 0; ?>"
                                <?= isset($domainExtentions['priority']) && $domainExtentions['priority'] == 1 ? 'checked' : '' ?>>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Giá bán</label>
                        <input type="number" class="form-control domain__price" name="price" value="<?php echo $domainExtentions['price']; ?>">
                        <?php if (!empty($errors['price'])): ?>
                            <small class="error text-danger"><?= htmlspecialchars(implode(', ', $errors['price'])) ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="col-6 mb-3">
                        <label class=" form-label">Thuộc tên miền</label>
                        <select class="form-select" id="id_status" name="type">
                            <option value="1" <?= ($domainExtentions['type'] == 1) ? 'selected' : '' ?>>VN</option>
                            <option value="0" <?= ($domainExtentions['type'] == 2) ? 'selected' : '' ?>>Quốc tế</option>
                        </select>
                        <?php if (!empty($errors['type'])): ?>
                            <small class="error"><?= htmlspecialchars(implode(', ', $errors['type'])) ?></small>
                        <?php endif; ?>
                    </div>
                </div>
                <label class="form-label">Khuyến mãi</label>
                <div class="row">
                    <div class="col-6">
                        <div class="form-check d-flex align-items-center mb-3">
                            <input class="form-check-input me-2" type="radio" name="percent" id="percent1" value="option1" <?php echo !empty($domainExtentions['percent']) || (empty($domainExtentions['percent']) && empty($domainExtentions['price_km'])) ? "checked" : ""; ?>>
                            <label class="form-check-label" for="percent1">Theo phần trăm</label>
                        </div>
                        <div class="input-group">
                            <input type="number" class="form-control" name="percent" id="percent_input" value="<?php echo $domainExtentions['percent']; ?>">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-check d-flex align-items-center mb-3">
                            <input class="form-check-input me-2" type="radio" name="percent" id="priceKM2" value="option2" <?php echo !empty($domainExtentions['price_km']) ? "checked" : ""; ?>>
                            <label class="form-check-label" for="priceKM2">Theo giá</label>
                        </div>
                        <div class="input-group">
                            <input type="number" class="form-control" name="price_km" id="price_km_input" value="<?php echo $domainExtentions['price_km']; ?>" disabled>
                            <span class="input-group-text">đ</span>
                        </div>
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