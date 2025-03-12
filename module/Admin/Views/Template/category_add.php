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
    <?php if (!empty($success)) : ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-save" style="width: max-content;">Lưu thay đổi</button>
        </div>

        <div class="row">
            <div class="mb-3 col-6 mx-auto">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Loại template</label>
                        <input type="text" class="form-control domain__name" name="name" value="<?php echo !empty($old['name']) ? $old['name'] : ""; ?>">
                        <?php if (!empty($errors['name'])): ?>
                            <small class="error text-danger"><?= htmlspecialchars(implode(', ', $errors['name'])) ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Hình</label>
                        <input type="file" class="form-control " name="images">
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Leave a comment here" id="description" rows="5" name="description"><?php echo !empty($old['description']) ? $old['description'] : ""; ?></textarea>
                            <label for="description">Mô tả</label>
                        </div>
                        <?php if (!empty($errors['description'])): ?>
                            <small class="error text-danger"><?= htmlspecialchars(implode(', ', $errors['description'])) ?></small>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

    </form>
</div>