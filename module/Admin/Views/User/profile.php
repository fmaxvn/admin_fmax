<div class="container">
    <h2 class="text-center text-primary">Thông tin cá nhân</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form action="/admin/user/profile" method="POST" enctype="multipart/form-data">
        <div class="text-center mb-3">
            <img src="<?= !empty($user['avatar']) ? $this->getImageUrl($user['avatar'], "100x100"):""?>" class="profile-img" alt="Avatar">
            <input type="file" name="avatar" class="form-control mt-2">
        </div>

        <div class="mb-3">
            <label class="form-label">Tên đăng nhập:</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" disabled>
        </div>

        <!-- Nhập thông tin cá nhân -->
        <?php
        $fields = [
            'fullname' => 'Họ và tên',
            'email' => 'Email',
            'mobile' => 'Số điện thoại',
            'address' => 'Địa chỉ',
            'notes' => 'Ghi chú',
            'facebook' => 'Facebook',
            'youtube' => 'YouTube',
            'twitter' => 'Twitter',
            'instagram' => 'Instagram',
            'desc' => 'Mô tả bản thân',
            'user_omicall' => 'Omicall Username',
            'pass_omicall' => 'Omicall Password'
        ];

        foreach ($fields as $name => $label):
        ?>
            <div class="mb-3">
                <label for="<?= $name ?>" class="form-label"><?= $label ?>:</label>
                <input type="text" name="<?= $name ?>" id="<?= $name ?>" class="form-control" value="<?= htmlspecialchars($user[$name] ?? '') ?>">
            </div>
        <?php endforeach; ?>

        <!-- Chọn nhóm user -->
        <div class="mb-3">
            <label class="form-label">Nhóm người dùng:</label>
            <select name="id_usergroup" class="form-control">
                <option value="1" <?= $user['id_usergroup'] == 1 ? 'selected' : '' ?>>Admin</option>
                <option value="2" <?= $user['id_usergroup'] == 2 ? 'selected' : '' ?>>Nhân viên</option>
                <option value="3" <?= $user['id_usergroup'] == 3 ? 'selected' : '' ?>>Khách hàng</option>
            </select>
        </div>

        <!-- Chọn giới tính -->
        <div class="mb-3">
            <label class="form-label">Giới tính:</label>
            <select name="sex" class="form-control">
                <option value="male" <?= $user['sex'] == 'male' ? 'selected' : '' ?>>Nam</option>
                <option value="female" <?= $user['sex'] == 'female' ? 'selected' : '' ?>>Nữ</option>
            </select>
        </div>

        <!-- Checkbox trạng thái -->
        <?php
        $checkboxes = [
            'is_team' => 'Thành viên nhóm',
            'is_type' => 'Loại user đặc biệt',
            'online' => 'Trạng thái Online'
        ];

        foreach ($checkboxes as $name => $label):
        ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="<?= $name ?>" id="<?= $name ?>" value="1" <?= !empty($user[$name]) ? 'checked' : '' ?>>
                <label class="form-check-label" for="<?= $name ?>"><?= $label ?></label>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary w-100 mt-3">Cập nhật thông tin</button>
    </form>
</div>