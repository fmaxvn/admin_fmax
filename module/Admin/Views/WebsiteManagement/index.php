<h2>Danh sách website</h2>

<div class="d-flex justify-content-between mb-4">
    <div>
        <form method="GET" action="/admin/website-management/index" class="d-flex">
            <input type="text" class="form-control me-2" placeholder="Tìm kiếm..." name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
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
            <th>Domain</th>
            <th>Database Name</th>
            <th>ID PCN Group</th>
            <th>Type Template</th>
            <th>Domain Old</th>
            <th>Thao tác</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($domain)): ?>
            <?php foreach ($domain as $val): ?>
                <tr>
                    <td><?= htmlspecialchars($val['domain'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($val['database_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($val['id_pcngroup'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($val['type_template'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($val['domain_old'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
<!--                        <div class="btn-group">-->
<!--                            <a href="/admin/website-management/edit/id/--><?//= $val['id'] ?><!--" class="btn btn-sm btn-primary me-1">Sửa</a>-->
<!--                            <button type="button" class="btn btn-sm btn-danger delete-user" data-id="--><?//= $val['id'] ?><!--">Xóa</button>-->
<!--                        </div>-->
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

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa domain này?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Xóa</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript cho chức năng xóa -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-user');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        let userIdToDelete = null;

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                userIdToDelete = this.getAttribute('data-id');
                deleteModal.show();
            });
        });

        document.getElementById('confirmDelete').addEventListener('click', async function() {
            if (!userIdToDelete) return;

            const formData = new FormData();
            formData.append('id', userIdToDelete);

            try {
                const response = await fetch('/admin/website-management/delete', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                deleteModal.hide();

                if (data.status === 'success') {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi xóa domain');
                }
            } catch (error) {
                console.error('Error:', error);
                deleteModal.hide();
                alert('Đã xảy ra lỗi khi xóa domain');
            }
        });
    });
</script>
