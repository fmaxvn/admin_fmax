<?php $domain = $domain ?? []; ?>

<div class="container mt-5">
    <h2 class="text-center">Chỉnh sửa Website</h2>

    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($success)) : ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL_ADMIN ?>/website/edit/id/<?= $domain['id'] ?? ''; ?>" method="POST">
        <input type="hidden" name="id" value="<?= $domain['id'] ?? ''; ?>">

        <div class="mb-3">
            <label class="form-label">Domain</label>
            <input type="text" class="form-control" name="domain" value="<?= $domain['domain'] ?? ''; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Database Name</label>
            <input type="text" class="form-control" name="database_name" value="<?= $domain['database_name'] ?? ''; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">ID PCN Group</label>
            <input type="text" class="form-control" name="id_pcngroup" value="<?= $domain['id_pcngroup'] ?? ''; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Type Template</label>
            <input type="text" class="form-control" name="type_template" value="<?= $domain['type_template'] ?? ''; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Domain Old</label>
            <input type="text" class="form-control" name="domain_old" value="<?= $domain['domain_old'] ?? ''; ?>">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
