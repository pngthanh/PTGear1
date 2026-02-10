<?php
require_once __DIR__ . '/../../models/connect.php';
require_once __DIR__ . '/../../models/Product.php';

$db = new Database();
$conn = $db->conn;
$productModel = new Product($conn);

$id = $_POST['id'] ?? 0;

if ($id > 0 && $productModel->deleteProduct($id)) {
    echo "success";
} else {
    echo "error";
}

$db->close();
