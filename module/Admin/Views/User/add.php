<div class="container">
    <h2 class="text-center text-primary mb-4">Thêm mới tài khoản</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <strong>Vui lòng sửa các lỗi sau:</strong>
            <ul>
                <?php foreach ($errors as $field => $fieldErrors): ?>
                    <?php foreach ($fieldErrors as $errorMsg): ?>
                        <li><?= htmlspecialchars($errorMsg) ?></li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="/admin/user/add" method="POST" enctype="multipart/form-data" id="add-user-form">
        <!-- Avatar upload -->
        <div class="text-center mb-4">
            <div class="avatar-container position-relative mx-auto" style="width: 150px; height: 150px;">
                <img id="avatar-preview" src="<?= URL_ASSETS ?>/images/default-avatar.png" 
                     class="rounded-circle shadow" alt="Avatar" 
                     style="width: 150px; height: 150px; object-fit: cover;">
                <div class="avatar-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center rounded-circle"
                     style="background: rgba(0,0,0,0.5); opacity: 0; transition: opacity 0.3s;">
                    <label for="avatar" class="text-white mb-0" style="cursor: pointer;">
                        <i class="bi bi-camera-fill fs-3"></i><br>
                        Chọn avatar
                    </label>
                </div>
            </div>
            
            <!-- Input file ảnh -->
            <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*" onchange="previewAvatar(this)">
            
            <small class="d-block mt-2 text-muted">Nhấp vào ảnh để chọn avatar</small>
        </div>

        <!-- Thông tin đăng nhập - Hàng 1 -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="username" class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                    <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($old['username'] ?? '') ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                </div>
            </div>
        </div>

        <!-- Thông tin cá nhân - Hàng 2 -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fullname" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" name="fullname" id="fullname" class="form-control" value="<?= htmlspecialchars($old['fullname'] ?? '') ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                </div>
            </div>
        </div>

        <!-- Thông tin cá nhân - Hàng 3 -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="mobile" class="form-label">Số điện thoại</label>
                    <input type="tel" name="mobile" id="mobile" class="form-control" value="<?= htmlspecialchars($old['mobile'] ?? '') ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="sex" class="form-label">Giới tính</label>
                    <select name="sex" id="sex" class="form-control">
                        <option value="male" <?= isset($old['sex']) && $old['sex'] == 'male' ? 'selected' : '' ?>>Nam</option>
                        <option value="female" <?= isset($old['sex']) && $old['sex'] == 'female' ? 'selected' : '' ?>>Nữ</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="id_usergroup" class="form-label">Nhóm người dùng</label>
                    <select name="id_usergroup" id="id_usergroup" class="form-control">
                        <option value="1" <?= isset($old['id_usergroup']) && $old['id_usergroup'] == 1 ? 'selected' : '' ?>>Admin</option>
                        <option value="2" <?= isset($old['id_usergroup']) && $old['id_usergroup'] == 2 ? 'selected' : '' ?>>Nhân viên</option>
                        <option value="3" <?= isset($old['id_usergroup']) && $old['id_usergroup'] == 3 ? 'selected' : '' ?> selected>Khách hàng</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Địa chỉ -->
        <div class="form-group mb-3">
            <label for="address" class="form-label">Địa chỉ</label>
            <textarea name="address" id="address" class="form-control" rows="2"><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
        </div>

        <!-- Checkbox các tùy chọn -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_team" id="is_team" value="1" <?= !empty($old['is_team']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_team">Thành viên nhóm</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_type" id="is_type" value="1" <?= !empty($old['is_type']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_type">Loại user đặc biệt</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="online" id="online" value="1" <?= !empty($old['online']) ? 'checked' : '' ?> checked>
                    <label class="form-check-label" for="online">Trạng thái hoạt động</label>
                </div>
            </div>
        </div>

        <!-- Nút submit và quay lại -->
        <div class="row">
            <div class="col-md-6">
                <a href="/admin/user/index" class="btn btn-secondary w-100">Quay lại danh sách</a>
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary w-100">Tạo mới tài khoản</button>
            </div>
        </div>
    </form>
</div>

<!-- CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
<style>
    .avatar-container:hover .avatar-overlay {
        opacity: 1 !important;
    }
    .form-label {
        font-weight: 500;
    }
</style>

<!-- JavaScript để preview avatar -->
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Validate form trước khi submit
    const form = document.getElementById('add-user-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const fullname = document.getElementById('fullname').value.trim();
            const email = document.getElementById('email').value.trim();
            let isValid = true;
            
            // Kiểm tra username
            if (username.length < 3) {
                alert('Tên đăng nhập phải có ít nhất 3 ký tự');
                isValid = false;
            }
            
            // Kiểm tra password
            if (password.length < 6) {
                alert('Mật khẩu phải có ít nhất 6 ký tự');
                isValid = false;
            }
            
            // Kiểm tra confirm password
            if (password !== confirmPassword) {
                alert('Xác nhận mật khẩu không khớp');
                isValid = false;
            }
            
            // Kiểm tra fullname
            if (fullname === '') {
                alert('Vui lòng nhập họ và tên');
                isValid = false;
            }
            
            // Kiểm tra email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Vui lòng nhập email hợp lệ');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Hiệu ứng hover cho avatar
    const avatarContainer = document.querySelector('.avatar-container');
    const avatarOverlay = avatarContainer.querySelector('.avatar-overlay');
    
    if (avatarContainer && avatarOverlay) {
        avatarContainer.addEventListener('mouseenter', function() {
            avatarOverlay.style.opacity = '1';
        });
        
        avatarContainer.addEventListener('mouseleave', function() {
            avatarOverlay.style.opacity = '0';
        });
    }
});
</script>