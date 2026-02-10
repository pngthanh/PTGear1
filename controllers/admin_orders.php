<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php?page=login');
    exit;
}

require_once __DIR__ . '/../models/connect.php';
require_once __DIR__ . '/../models/Order.php';

$db = new Database();
$conn = $db->conn;

// lấy hết đơn hàng
$orders = Order::getAllOrders($conn);

// nạp view
$page = 'admin_orders';
require_once __DIR__ . '/../views/v_admin_orders.php';

$db->close();
