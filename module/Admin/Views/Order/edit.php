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
    <h3>Chỉnh Sửa Đơn Hàng</h3>


    <!-- ✅ FORM CHỈNH SỬA ĐƠN HÀNG -->
    <form action="" method="post">
        <!-- ID đơn hàng (Ẩn) -->
        <!-- <input type="hidden" name="id" value="<?= htmlspecialchars($cartGlobal['id'] ?? '') ?>"> -->
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-save" style="width: max-content;">Lưu thay đổi</button>
        </div>

        <div class="row">
            <h6 class="fw-bold">Thông tin website</h6>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="code">Tên domain</label>
                    <input type="text" class="form-control" value="<?= $infoDomain['domain'] ?>" disabled>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="total_price">Tên database</label>
                    <input type="text" class="form-control" value="<?= $infoDomain['database_name'] ?>" disabled>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="id_status">Trạng thái Public</label>
                    <select class="form-select" id="id_status" name="id_status" disabled>
                        <option value="1" <?= ($infoDomain['public_website'] == 1) ? 'selected' : '' ?>>Đã public</option>
                        <option value="0" <?= ($infoDomain['public_website'] == 0) ? 'selected' : '' ?>>Chưa public</option>
                    </select>
                    <?php if (!empty($errors['id_status'])): ?>
                        <small class="error"><?= htmlspecialchars(implode(', ', $errors['id_status'])) ?></small>
                    <?php endif; ?>
                </div>
            </div>


            <hr>
            <!-- Hàng 1 -->
            <div class="col-md-4">
                <div class="form-group">
                    <label for="code">Mã đơn hàng</label>
                    <input type="text" class="form-control" id="code" name="code" value="<?= htmlspecialchars($cartGlobal['code'] ?? '') ?>" disabled>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="total_price">Tổng tiền</label>
                    <input type="number" class="form-control" id="total_price" name="total_price" value="<?= htmlspecialchars($cartGlobal['total_price'] ?? '') ?>" disabled>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="id_status">Trạng thái</label>
                    <select class="form-select" id="id_status" name="id_status">
                        <?php foreach ($cartStatusList as $status): ?>
                            <option value="<?= $status['id'] ?>" <?= isset($cartGlobal['id_status']) && $cartGlobal['id_status'] == $status['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($status['name'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['id_status'])): ?>
                        <small class="error"><?= htmlspecialchars(implode(', ', $errors['id_status'])) ?></small>
                    <?php endif; ?>
                </div>
            </div>
            <hr>

            <!-- Hàng 2 -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Tên khách hàng</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($cartGlobal['name'] ?? '') ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mobile">Số điện thoại</label>
                    <input type="number" class="form-control" id="mobile" name="mobile" value="<?= htmlspecialchars($cartGlobal['mobile'] ?? '') ?>">
                    <?php if (!empty($errors['mobile'])): ?>
                        <small class="error text-danger"><?= htmlspecialchars(implode(', ', $errors['mobile'])) ?></small>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="address">Địa chỉ</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($cartGlobal['address'] ?? '') ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tax_number">Mã số thuế</label>
                    <input type="text" class="form-control" id="tax_number" name="tax_number" value="<?= htmlspecialchars($cartGlobal['tax_number'] ?? '') ?>">
                </div>
            </div>

        </div>

    </form>

    <hr>

    <!-- ✅ DANH SÁCH CHI TIẾT ĐƠN HÀNG -->
    <h4 class="mt-4">Chi các gói Đơn Hàng</h4>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Ngày thanh toán cuối</th>
                <th>Ngày gia hạn</th>
                <th>Phương thức thanh toán</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($listCartDetail)): ?>
                <?php foreach ($listCartDetail as $key => $detail): ?>
                    <tr>
                        <td><?= htmlspecialchars($key + 1) ?></td>
                        <td><?= htmlspecialchars($detail['name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($detail['qty'] ?? '') ?></td>
                        <td><?= number_format($detail['price'] ?? 0, 0, ',', '.') ?> đ</td>
                        <td><?= htmlspecialchars($detail['last_payment_date'] ?? '') ?></td>
                        <td><?= htmlspecialchars($detail['next_renewal_date'] ?? '') ?></td>
                        <td><?= htmlspecialchars(!empty($detail['id_payment']) ? 'VNPAY' : 'MOMO') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Không có sản phẩm trong đơn hàng</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>