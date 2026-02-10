<?php
require_once __DIR__ . '/../../models/connect.php';
require_once __DIR__ . '/../../models/Product.php';

$cat_id = intval($_POST['cat_id'] ?? 0);
$sub_id = $_POST['sub_id'] ?? 'new';
$name = trim($_POST['name'] ?? '');

if (empty($name) || $cat_id == 0) {
    echo "Lỗi: Vui lòng nhập đầy đủ thông tin.";
    exit;
}

$db = new Database();
$conn = $db->conn;
$productModel = new Product($conn);
$success = false;

if ($sub_id === 'new') {
    // Thêm mới
    $success = $productModel->createSubcategory($cat_id, $name);
} else {
    // Cập nhật
    $success = $productModel->updateSubcategory(intval($sub_id), $name);
}

if ($success) {
    echo "success";
} else {
    echo "Lỗi: Thao tác CSDL thất bại.";
}

$db->close();
