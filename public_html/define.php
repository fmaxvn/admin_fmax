<?php

// Định nghĩa đường dẫn gốc của dự án
define('BASE_PATH', dirname(__DIR__)); 

// Định nghĩa đường dẫn thư mục core
define('CORE_PATH', BASE_PATH . '/Core');

// Định nghĩa đường dẫn thư mục public (public_html)
define('PUBLIC_PATH', BASE_PATH . '/public_html');

define('TOKEN', '4m6YFh8YhFI6PGenbIN5QheHZXPqXI8zotMrs5QWjVzKhBtMj0zRvMf1xucn');

// Định nghĩa đường dẫn thư mục app (nếu có)
define('APP_PATH', BASE_PATH . '/app');

// Định nghĩa đường dẫn thư mục views (nếu có)
define('VIEWS_PATH', BASE_PATH . '/views');

// Định nghĩa đường dẫn thư mục storage (nếu có)
define('STORAGE_PATH', BASE_PATH . '/storage');

// Định nghĩa URL gốc của website (thay đổi tùy theo tên miền)
define('BASE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST']);

define('BASE_URL_ADMIN', BASE_URL.'/admin');
define('URL_ASSETS',  BASE_URL.'/assets');
define('URL_UPLOADS',  BASE_URL.'/uploads');

define('UPLOADS_DIR', __DIR__ . '/uploads'); // Đường dẫn thư mục lưu ảnh trên server
define('URL_DEFAULT_IMAGE', URL_ASSETS.'/images/default.jpg'); // Ảnh mặc định khi file không tồn tại

