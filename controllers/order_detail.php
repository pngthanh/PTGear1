<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

// lấy ID đơn hàng
$order_id = $_GET['id'] ?? null;
if (!$order_id) {
    header('Location: index.php?page=orders');
    exit;
}

require_once __DIR__ . '/../models/connect.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Order.php';

$db = new Database();
$conn = $db->conn;

$user_id = $_SESSION['user_id'];

// lấy thông tin user
$user = User::getUserById($conn, $user_id);
if (!$user) {
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}

// lấy chi tiết đơn hàng
$order = Order::getOrderDetailsById($conn, $order_id, $user_id);
if (!$order) {
    header('Location: index.php?page=orders');
    exit;
}

require_once __DIR__ . '/../views/v_order_detail.php';

$db->close();
