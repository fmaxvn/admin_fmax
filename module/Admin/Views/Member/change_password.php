<div class="container">
    <h2 class="text-center text-primary">Đổi mật khẩu</h2>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>


    <form action="/admin/member/change_password/id/<?= $memberId ?>" method="POST" onsubmit="return validateForm()">
        <div class="mb-3">
            <label for="new_password" class="form-label">Mật khẩu mới:</label>
            <!-- <input type="password" name="new_password" id="new_password" class="form-control" required minlength="6"> -->
            <div class="input-group">
                <input type="password" name="new_password" id="new_password" class="form-control" required minlength="6">
                <span class="input-group-text toggle-password" data-target="new_password">
                    <i class="ph ph-eye"></i>
                </span>
            </div>
            <?php if (!empty($errors['new_password'])): ?>
                <small class="error text-danger"><?= htmlspecialchars(implode(', ', $errors['new_password'])) ?></small>
            <?php endif; ?>
            <p class="">Lưu ý: <br>
                - Mật khẩu phải từ 8-16 ký tự <br>
                - Có ít nhất 1 chữ hoa, 1 chữ thường, 1 số <br>
                - Có 1 ký tự đặc biệt (!@#$%^&*()_+<>?).</p>
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Xác nhận mật khẩu:</label>
            <div class="input-group">
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                <span class="input-group-text toggle-password" data-target="confirm_password">
                    <i class="ph ph-eye"></i>
                </span>
            </div>
            <!-- <input type="password" name="confirm_password" id="confirm_password" class="form-control" required> -->
            <?php if (!empty($errors['confirm_password'])): ?>
                <small class="error"><?= htmlspecialchars(implode(', ', $errors['confirm_password'])) ?></small>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <small class="error text-danger"><?= htmlspecialchars($errors) ?></small>
            <?php endif; ?>
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

    document.querySelectorAll(".toggle-password").forEach(function(toggle) {
        toggle.addEventListener("click", function() {
            let targetId = this.getAttribute("data-target");
            let inputField = document.getElementById(targetId);
            let icon = this.querySelector("i");

            if (inputField.type === "password") {
                inputField.type = "text";
                icon.classList.remove("ph-eye");
                icon.classList.add("ph-eye-slash");
            } else {
                inputField.type = "password";
                icon.classList.remove("ph-eye-slash");
                icon.classList.add("ph-eye");
            }
        });
    });
</script>