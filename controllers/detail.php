<?php
session_start();

require_once __DIR__ . '/../models/connect.php';
require_once __DIR__ . '/../models/Product.php';

$db = new Database();
$conn = $db->conn;
$productModel = new Product($conn);

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($productId == 0) {
    header("Location: index.php?page=products&category=1");
    exit;
}

$product = $productModel->getProductById($productId);
if (!$product) {
    header("Location: index.php?page=products&category=1");
    exit;
}

$discount = $product['discount_percent'];
$newPrice = $product['price'];
if ($discount > 0) {
    $newPrice = $product['price'] * (100 - $discount) / 100;
}

$pageTitle = htmlspecialchars($product['name']);

require_once __DIR__ . '/../views/v_detail.php';

$db->close();
