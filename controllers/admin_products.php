<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php?page=home");
    exit;
}

require_once __DIR__ . '/../models/connect.php';
require_once __DIR__ . '/../models/Product.php';

$db = new Database();
$conn = $db->conn;
$productModel = new Product($conn);

// Lấy giá trị filter từ URL
$filterCategory = isset($_GET['category']) ? intval($_GET['category']) : 0;
$filterSubcategory = isset($_GET['subcategory']) ? intval($_GET['subcategory']) : 0;

// Lấy danh sách
$products = $productModel->getAdminProducts($filterCategory, $filterSubcategory);

// Lấy tất cả danh mục
$categories = $productModel->getAllCategories();

// Lấy danh mục con dựa trên category đã lọc 
$subcategories = [];
if ($filterCategory > 0) {
    $subcategories = $productModel->getSubcategoriesByCategoryId($filterCategory);
}

// Biến cho sidebar
$pageTitle = "Quản lý Sản phẩm";
$page = 'admin_products';

require_once __DIR__ . '/../views/v_admin_products.php';

$db->close();
