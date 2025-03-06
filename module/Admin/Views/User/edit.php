<?php $user = $user ?? []; ?>

<div class="container mt-5">
    <h2 class="text-center">Chỉnh sửa Người dùng</h2>

    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($success)) : ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL_ADMIN ?>/user/edit/id/<?= $user['id'] ?? ''; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $user['id'] ?? ''; ?>">
        <div class="mb-3">
            <label class="form-label">Họ và Tên</label>
            <input type="text" class="form-control" name="fullname" value="<?= $user['fullname'] ?? ''; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= $user['email'] ?? ''; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" name="mobile" value="<?= $user['mobile'] ?? ''; ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" class="form-control" name="address" value="<?= $user['address'] ?? ''; ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh đại diện</label>
            <input type="file" class="form-control" name="avatar">
            <?php if (!empty($user['avatar'])) : ?>
                <img src="<?= $this->getImageUrl($user['avatar']); ?>" alt="Avatar" class="img-thumbnail mt-2" width="100">
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Nhóm người dùng</label>
            <select class="form-control" name="id_usergroup">
                <option value="1" <?= ($user['id_usergroup'] ?? '') == '1' ? 'selected' : ''; ?>>Admin</option>
                <option value="2" <?= ($user['id_usergroup'] ?? '') == '2' ? 'selected' : ''; ?>>Người dùng</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Giới tính</label>
            <select class="form-control" name="sex">
                <option value="male" <?= ($user['sex'] ?? '') == 'male' ? 'selected' : ''; ?>>Nam</option>
                <option value="female" <?= ($user['sex'] ?? '') == 'female' ? 'selected' : ''; ?>>Nữ</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
