<style>
    /* Cấu trúc chính */

    h2 {
        text-align: center;
        color: #333;
    }

    /* Cảnh báo */
    .alert {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
    }

    .error {
        color: red;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    /* Form */
    .edit-form {
        display: flex;
        flex-direction: column;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    .form-control,
    .form-control-file {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    .form-control-file {
        border: none;
    }

    /* Nút bấm */
    .btn {
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
        width: 100%;
        text-transform: uppercase;
        font-weight: bold;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    /* Avatar */
    .avatar-preview {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        display: block;
        margin-bottom: 10px;
    }
</style>
<div class="row">
    <div class="col-6 mx-auto">
        <form action="" method="POST" enctype="multipart/form-data" class="edit-form">
            <div class="form-group">
                <label for="fullname">Họ và tên</label>
                <input type="text" class="form-control" id="fullname" name="fullname"
                    value="<?= htmlspecialchars($old['fullname'] ?? $member['fullname'] ?? '') ?>" required>
                <?php if (!empty($errors['fullname'])): ?>
                    <small class="error"><?= htmlspecialchars(implode(', ', $errors['fullname'])) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?= htmlspecialchars($old['email'] ?? $member['email'] ?? '') ?>" required>
                <?php if (!empty($errors['email'])): ?>
                    <small class="error"><?= htmlspecialchars(implode(', ', $errors['email'])) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="mobile">Số điện thoại</label>
                <input type="text" class="form-control" id="mobile" name="mobile"
                    value="<?= htmlspecialchars($old['mobile'] ?? $member['mobile'] ?? '') ?>">
                <?php if (!empty($errors['mobile'])): ?>
                    <small class="error"><?= htmlspecialchars(implode(', ', $errors['mobile'])) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="birthday">Ngày sinh</label>
                <input type="date" class="form-control" id="birthday" name="birthday"
                    value="<?= htmlspecialchars($old['birthday'] ?? $member['birthday'] ?? '') ?>">
                <?php if (!empty($errors['birthday'])): ?>
                    <small class="error"><?= htmlspecialchars(implode(', ', $errors['birthday'])) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="sex">Giới tính</label>
                <select class="form-control" id="sex" name="sex">
                    <option value="0" <?= ((isset($old['sex']) ? $old['sex'] : $member['sex']) == '0') ? 'selected' : '' ?>>Nam</option>
                    <option value="1" <?= ((isset($old['sex']) ? $old['sex'] : $member['sex']) == '1') ? 'selected' : '' ?>>Nữ</option>
                </select>
                <?php if (!empty($errors['sex'])): ?>
                    <small class="error"><?= htmlspecialchars(implode(', ', $errors['sex'])) ?></small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Avatar</label><br>
                <?php if (!empty($member['image'])): ?>
                    <img src="<?= $this->getImageUrl($member['image']) ?>" alt="Avatar" class="avatar-preview"><br>
                <?php endif; ?>
                <input type="file" class="form-control-file" name="avatar">
                <?php if (!empty($errors['avatar'])): ?>
                    <small class="error"><?= htmlspecialchars(implode(', ', $errors['avatar'])) ?></small>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
</div>

<script>
    function validateForm() {
        let fullname = document.getElementById("fullname").value.trim();
        let email = document.getElementById("email").value.trim();
        let mobile = document.getElementById("mobile").value.trim();
        let birthday = document.getElementById("birthday").value;

        if (fullname === "") {
            alert("Họ và tên không được để trống");
            return false;
        }

        let emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailRegex.test(email)) {
            alert("Email không hợp lệ");
            return false;
        }

        let phoneRegex = /^[0-9]{10,11}$/;
        if (mobile !== "" && !phoneRegex.test(mobile)) {
            alert("Số điện thoại không hợp lệ (chỉ nhập 10-11 số)");
            return false;
        }

        if (birthday !== "" && new Date(birthday) > new Date()) {
            alert("Ngày sinh không được lớn hơn ngày hiện tại");
            return false;
        }

        return true;
    }
</script>