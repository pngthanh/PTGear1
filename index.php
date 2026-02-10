<?php
$page = $_GET['page'] ?? 'home';

if ($page === 'lienhe') {
    require_once __DIR__ . '/views/v_lienhe.php';
    exit(); // Dừng lại để không chạy code bên dưới
}

$controllerPath = __DIR__ . '/controllers/' . $page . '.php';

if (file_exists($controllerPath)) {
    require_once $controllerPath;
} else {

    http_response_code(404);
    echo "Trang không tồn tại.";
}
