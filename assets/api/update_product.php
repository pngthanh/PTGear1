<?php
require_once __DIR__ . '/../../models/connect.php';
require_once __DIR__ . '/../../models/Product.php';

if (empty($_POST['id']) || empty($_POST['name'])) {
    echo "Lỗi: Thiếu ID hoặc Tên sản phẩm.";
    exit;
}

$db = new Database();
$conn = $db->conn;
$productModel = new Product($conn);

$file = (isset($_FILES['image']) && $_FILES['image']['error'] == 0) ? $_FILES['image'] : null;

$result = $productModel->updateProduct($_POST, $file);

if ($result === true) {
    echo "success";
} else {
    echo $result;
}

$db->close();
