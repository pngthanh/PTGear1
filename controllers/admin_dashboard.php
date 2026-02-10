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

// Gọi hàm thông qua $productModel
$stats = $productModel->getDashboardStats();
$revenue7Days = $productModel->getRevenueLast7Days();
$recentOrders = $productModel->getRecentOrders();
$categorySales = $productModel->getCategorySalesStructure();
$bestSellers = $productModel->getBestSellingProductsDashboard();

$chartLabels7Days = [];
$chartData7Days = [];
// Tạo mảng 7 ngày
$days = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $days[$date] = 0;
}
// Map doanh thu từ CSDL vào
foreach ($revenue7Days as $data) {
    if (isset($days[$data['order_date']])) {
        $days[$data['order_date']] = (float)$data['daily_revenue'];
    }
}
foreach ($days as $date => $revenue) {
    $chartLabels7Days[] = date('d/m', strtotime($date));
    $chartData7Days[] = $revenue;
}

// Cơ cấu danh mục
$chartLabelsCategory = [];
$chartDataCategory = [];
$chartColorsCategory = ['#3b82f6', '#10b981', '#f59e0b'];
foreach ($categorySales as $data) {
    $chartLabelsCategory[] = $data['category_name'];
    $chartDataCategory[] = (int)$data['items_sold'];
}

// Biến cho sidebar
$pageTitle = "Bảng Thông Kê";
$page = 'admin_dashboard';

require_once __DIR__ . '/../views/v_admin_dashboard.php';

$db->close();
