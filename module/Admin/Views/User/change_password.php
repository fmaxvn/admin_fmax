<div class="container">
    <h2 class="text-center text-primary">Đổi mật khẩu</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form action="/admin/user/change_password" method="POST" onsubmit="return validateForm()">
        <div class="mb-3">
            <label for="old_password" class="form-label">Mật khẩu cũ:</label>
            <input type="password" name="old_password" id="old_password" class="form-control" required>
            <div class="invalid-feedback">Mật khẩu cũ không được để trống.</div>
        </div>

        <div class="mb-3">
            <label for="new_password" class="form-label">Mật khẩu mới:</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required minlength="6">
            <div class="invalid-feedback">Mật khẩu mới phải có ít nhất 6 ký tự.</div>
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Xác nhận mật khẩu:</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            <div class="invalid-feedback">Mật khẩu xác nhận không khớp.</div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Đổi mật khẩu</button>
    </form>
</div>

<script>
    function validateForm() {
        let isValid = true;

        // Lấy giá trị nhập vào
        let oldPassword = document.getElementById("old_password");
        let newPassword = document.getElementById("new_password");
        let confirmPassword = document.getElementById("confirm_password");

        // Xóa lỗi cũ
        oldPassword.classList.remove("is-invalid");
        newPassword.classList.remove("is-invalid");
        confirmPassword.classList.remove("is-invalid");

        // Kiểm tra mật khẩu cũ
        if (oldPassword.value.trim() === "") {
            oldPassword.classList.add("is-invalid");
            isValid = false;
        }

        // Kiểm tra mật khẩu mới
        if (newPassword.value.length < 6) {
            newPassword.classList.add("is-invalid");
            isValid = false;
        }

        // Kiểm tra mật khẩu xác nhận
        if (newPassword.value !== confirmPassword.value) {
            confirmPassword.classList.add("is-invalid");
            isValid = false;
        }

        return isValid;
    }
</script>