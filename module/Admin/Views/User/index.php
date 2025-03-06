<h2>Danh sách thành viên - Trang <?= htmlspecialchars($pages ?? '1', ENT_QUOTES, 'UTF-8') ?></h2>

<table border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Họ Tên</th>
        <th>Email</th>
        <th>Điện thoại</th>
        <th>Ngày tạo</th>
        <th>Trạng thái</th>
    </tr>

    <?php if (!empty($users)): ?>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($user['fullname'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($user['mobile'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($user['datecreate'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= !empty($user['online']) ? '🟢 Online' : '🔴 Offline' ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">Không có dữ liệu</td>
        </tr>
    <?php endif; ?>
</table>

<?php echo $pagination;?>

<style>
.pagination {
    text-align: center;
    margin: 20px 0;
}

.pagination ul {
    list-style: none;
    padding: 0;
    display: flex;
    justify-content: center;
}

.pagination li {
    margin: 0 5px;
}

.pagination a, .pagination span {
    display: block;
    padding: 8px 12px;
    background: #007bff;
    color: white;
    border-radius: 4px;
    text-decoration: none;
}

.pagination a:hover {
    background: #0056b3;
}

.pagination .active span {
    background: #0056b3;
    font-weight: bold;
}

.pagination .dots {
    padding: 8px 12px;
    color: #888;
}

</style>