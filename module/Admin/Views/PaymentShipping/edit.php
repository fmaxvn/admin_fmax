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
            <div class="col-4 mb-3">
                <label class="form-label">Tên website</label>
                <input type="text" readonly class="form-control apps__market__name" value="<?php echo $infoDomainPayment['domain']; ?>" disabled>
            </div>
            <div class="col-4 mb-3">
                <label class="form-label">Trạng thái website</label>
                <select class="form-select platform__select-custom" aria-label="Default select example" disabled>
                    <option value="0" <?php echo $infoDomainPayment['public_website'] == 0 ? 'selected' : ''; ?>>Chưa public</option>
                    <option value="1" <?php echo $infoDomainPayment['public_website'] == 1 ? 'selected' : ''; ?>>Đã public</option>
                </select>
            </div>
            <hr>
            <div class="col-4 mb-3">
                <label class="form-label">Mã thanh toán</label>
                <input type="text" readonly class="form-control apps__market__name" value="<?php echo $paymentShippingDetail['code_shipping']; ?>" disabled>
            </div>
            <div class="col-4 mb-3">
                <label class="form-label">Mã đơn hàng</label>
                <input type="text" readonly class="form-control apps__market__name" value="<?php echo $paymentShippingDetail['code_cart']; ?>" disabled>
            </div>
            <div class="col-4 mb-3">
                <label class="form-label">Tên loại vận chuyển</label>
                <input type="text" class="form-control apps__market__name" name="name" value="<?php echo $paymentShippingDetail['name']; ?>">
                <?php if (!empty($errors['name'])): ?>
                    <small class="error text-danger"><?= htmlspecialchars(implode(', ', $errors['name'])) ?></small>
                <?php endif; ?>
            </div>

            <div class="col-4 mb-3">
                <label class="form-label">Giá ship</label>
                <input type="number" class="form-control apps__market__price" name="total" value="<?php echo $paymentShippingDetail['total']; ?>">
                <?php if (!empty($errors['total'])): ?>
                    <small class="error text-danger"><?= htmlspecialchars(implode(', ', $errors['total'])) ?></small>
                <?php endif; ?>
            </div>
            <div class="col-4 mb-3">
                <label class="form-label">Loại</label>
                <select class="form-select platform__select-custom" aria-label="Default select example" name="type" disabled>
                    <option value="0" <?php echo $paymentShippingDetail['order_status'] == 0 ? 'selected' : ''; ?>>Chưa đăng đơn</option>
                    <option value="1" <?php echo $paymentShippingDetail['order_status'] == 1 ? 'selected' : ''; ?>>Đã đăng đơn</option>
                </select>
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