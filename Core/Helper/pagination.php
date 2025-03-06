<?php
    // ✅ **Kiểm tra và gán giá trị mặc định cho biến**
    $currentPage = $currentPage ?? 1;
    $totalPages = $totalPages ?? 1;
    $prevPage = isset($prevPage) ? $prevPage : ($currentPage > 1 ? $currentPage - 1 : 1);
    $nextPage = isset($nextPage) ? $nextPage : ($currentPage < $totalPages ? $currentPage + 1 : $totalPages);
    $baseUrl = $baseUrl ?? '';
    $visiblePages = $visiblePages ?? 5;
    $start = isset($start) ? $start : max(1, $currentPage - floor($visiblePages / 2));
    $end = isset($end) ? $end : min($totalPages, $start + $visiblePages - 1);
?>

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
.pagination .active a {
    background: #0056b3;
    font-weight: bold;
}
.pagination .disabled {
    background: #ccc;
    pointer-events: none;
}
.pagination .dots {
    padding: 8px 12px;
    color: #888;
}
</style>

<nav class="pagination">
    <ul>
        <!-- Nút "Trang Đầu" -->
        <li class="<?= $currentPage == 1 ? 'disabled' : '' ?>">
            <a href="<?= $baseUrl ?>/page/1">|<<</a>
        </li>

        <!-- Nút "Trước" -->
        <li class="<?= $currentPage == 1 ? 'disabled' : '' ?>">
            <a href="<?= $baseUrl ?>/page/<?= $prevPage ?>">&laquo; Trước</a>
        </li>

        <!-- Hiển thị trang đầu nếu cần -->
        <?php if ($currentPage > ($visiblePages - 2)): ?>
            <li><a href="<?= $baseUrl ?>/page/1">1</a></li>
            <?php if ($currentPage > ($visiblePages - 1)): ?>
                <li class="dots">...</li>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Hiển thị các trang gần hiện tại -->
        <?php for ($i = $start; $i <= $end; $i++): ?>
            <li class="<?= $i == $currentPage ? 'active' : '' ?>">
                <a href="<?= $baseUrl ?>/page/<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <!-- Hiển thị trang cuối nếu cần -->
        <?php if ($currentPage < $totalPages - ($visiblePages - 2)): ?>
            <?php if ($currentPage < $totalPages - ($visiblePages - 1)): ?>
                <li class="dots">...</li>
            <?php endif; ?>
            <li><a href="<?= $baseUrl ?>/page/<?= $totalPages ?>"><?= $totalPages ?></a></li>
        <?php endif; ?>

        <!-- Nút "Sau" -->
        <li class="<?= $currentPage == $totalPages ? 'disabled' : '' ?>">
            <a href="<?= $baseUrl ?>/page/<?= $nextPage ?>">Sau &raquo;</a>
        </li>

        <!-- Nút "Trang Cuối" -->
        <li class="<?= $currentPage == $totalPages ? 'disabled' : '' ?>">
            <a href="<?= $baseUrl ?>/page/<?= $totalPages ?>">>>|</a>
        </li>
    </ul>
</nav>
