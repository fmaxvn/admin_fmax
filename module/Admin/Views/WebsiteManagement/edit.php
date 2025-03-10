<?php $domain = $domain ?? []; ?>

<div class="container mt-5">
    <h2 class="text-center">Chỉnh sửa Website</h2>

    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($success)) : ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL_ADMIN ?>/website-management/edit/id/<?= $domain['id'] ?? ''; ?>" method="POST">
        <input type="hidden" name="id" value="<?= $domain['id'] ?? ''; ?>">

        <div class="mb-3">
            <label class="form-label">Domain</label>
            <input type="text" class="form-control" name="domain" value="<?= $domain['domain'] ?? ''; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Database Name</label>
            <input type="text" class="form-control" name="database_name" value="<?= $domain['database_name'] ?? ''; ?>" required>
        </div>

        <!-- ✅ Dropdown cho Type Template -->
        <div class="mb-3">
            <label class="form-label">Type Template</label>
            <select class="form-control" name="type_template" required>
                <option value="">Chọn Template</option>
                <?php foreach ($templates as $template): ?>
                    <option value="<?= $template['id']; ?>" <?= ($domain['type_template'] ?? '') == $template['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($template['name'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Folder</label>
            <input type="text" class="form-control" name="folder" value="<?= $domain['folder'] ?? ''; ?>">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
