<?php
require_once __DIR__ . '/../../models/connect.php';
require_once __DIR__ . '/../../models/Product.php';

if (empty($_POST['name']) || !isset($_FILES['image'])) {
    echo "Lỗi: Vui lòng nhập đầy đủ thông tin và chọn ảnh.";
    exit;
}

$db = new Database();
$conn = $db->conn;
$productModel = new Product($conn);

$result = $productModel->createProduct($_POST, $_FILES['image']);

if ($result === true) {
    echo "success";
} else {
    echo $result;
}

$db->close();
