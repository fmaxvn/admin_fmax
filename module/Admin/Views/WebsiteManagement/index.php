<h2>Danh sách website</h2>

<div class="d-flex justify-content-between mb-4">
    <div>
        <form method="GET" action="/admin/website-management/index" class="d-flex">
            <input type="text" class="form-control me-2" placeholder="Tìm kiếm domain..." name="search" value="<?= htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <select name="sort" class="form-select me-2" style="width: 150px;">
                <option value="desc" <?= (($_GET['sort'] ?? 'desc') == 'desc') ? 'selected' : '' ?>>Mới nhất</option>
                <option value="asc" <?= (($_GET['sort'] ?? '') == 'asc') ? 'selected' : '' ?>>Cũ nhất</option>
            </select>
            <button type="submit" class="btn btn-primary">Tìm</button>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>STT</th>
            <th>Domain</th>
            <th>Database Name</th>
            <th>Tên Template</th>
            <th>Folder</th>
            <th>Thao tác</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($domain)): ?>
            <?php $counter = 1;?>
            <?php foreach ($domain as $val): ?>
                <tr>
                    <td><?= $counter++ ?></td>
                    <td><?= htmlspecialchars($val['domain'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($val['database_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($val['template_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($val['folder'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <div class="btn-group">
                            <a href="/admin/website-management/edit/id/<?= $val['id'] ?>" class="btn btn-sm btn-primary me-1">Sửa</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">Không có dữ liệu</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Phân trang -->
<?php echo $pagination; ?>
