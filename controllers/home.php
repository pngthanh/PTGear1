<?php
require_once __DIR__ . '/../models/connect.php';
require_once __DIR__ . '/../models/Product.php';


$db = new Database();
$conn = $db->conn;
$productModel = new Product($conn);

// lấy data
$pageTitle = "PTGear - Trang chủ";
$flashSaleProducts = $productModel->getFlashSaleProducts(8);

// data cho các tab ưu đãi
$newProducts = $productModel->getNewestProducts(8);
$bestSellerProducts = $productModel->getBestSellingProducts(8);
// id 2 linh kiện
$featuredComponents = $productModel->getFeaturedProducts(2, 8);
// id 1 phụ kiện
$featuredAccessories = $productModel->getFeaturedProducts(1, 8);

require_once __DIR__ . '/../views/v_home.php';

$db->close();
