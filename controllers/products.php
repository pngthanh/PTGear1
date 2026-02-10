<?php
session_start();

require_once __DIR__ . '/../models/connect.php';
require_once __DIR__ . '/../models/Product.php';

$db = new Database();
$conn = $db->conn;
$productModel = new Product($conn);

$categoryId = isset($_GET['category']) ? intval($_GET['category']) : 1;
$filterSub = isset($_GET['subcategory']) ? intval($_GET['subcategory']) : 0;
$currentSort = $_GET['sort'] ?? '';

$category = $productModel->getCategoryName($categoryId);
$subcategories = $productModel->getSubcategories($categoryId);
$products = $productModel->getProductsFiltered($categoryId, $filterSub, $currentSort);

$pageTitle = "PTGear - " . ($category['name'] ?? 'Sản phẩm');

require_once __DIR__ . '/../views/v_products.php';

$db->close();
