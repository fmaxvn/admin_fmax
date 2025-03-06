<?php


spl_autoload_register(function ($class) {
    // Chuyển namespace thành đường dẫn file
    $file = BASE_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    } else {
        die("Không tìm thấy file: $file");
    }
});
