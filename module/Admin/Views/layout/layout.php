<?php
/**
 * Layout chính cho tất cả trang
 *
 * @var string $title Tiêu đề trang
 * @var string $viewPath Đường dẫn file View cần load
 * @var array $data Dữ liệu cần truyền vào View
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Trang quản lý') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { display: flex; flex-direction: column; min-height: 100vh; }
        header { background: #007bff; color: white; padding: 15px; text-align: center; font-size: 20px; }
        .wrapper { display: flex; flex: 1; }
        aside { width: 220px; background: #f4f4f4; padding: 20px; flex-shrink: 0; }
        .content { flex: 1; display: flex; flex-direction: column; }
        main { flex: 1; padding: 20px; background: #fff; }
        footer { background: #333; color: white; padding: 10px; text-align: center; width: 100%; }
        ul { list-style: none; padding: 0; }
        ul li { margin: 10px 0; }
        ul li a { text-decoration: none; color: #333; display: block; padding: 5px; }
        ul li a:hover { background: #ddd; }
    </style>
</head>
<body>

    <?php require BASE_PATH . "/module/Admin/Views/layout/blocks/header.php"; ?>
    <div class="wrapper">
        <?php require BASE_PATH . "/module/Admin/Views/layout/blocks/sidebar.php"; ?>

        <div class="content">
            <main>
                <?php
                if (isset($viewPath) && file_exists($viewPath)) {
                    require $viewPath;
                } else {
                    echo "Lỗi: Không tìm thấy file View.";
                }
                ?>
            </main>

            <footer>
                © <?= date('Y') ?> Fmax - All rights reserved.
            </footer>
        </div>
    </div>

</body>
</html>
