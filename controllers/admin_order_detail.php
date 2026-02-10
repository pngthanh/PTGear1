<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php?page=login');
    exit;
}

$order_id = $_GET['id'] ?? null;
if (!$order_id) {
    header('Location: index.php?page=admin_orders');
    exit;
}

require_once __DIR__ . '/../models/connect.php';
require_once __DIR__ . '/../models/Order.php';

$db = new Database();
$conn = $db->conn;

$order = Order::adminGetOrderDetailsById($conn, (int)$order_id);

if (!$order) {
    header('Location: index.php?page=admin_orders');
    exit;
}

$page = 'admin_orders';
require_once __DIR__ . '/../views/v_admin_order_detail.php';

$db->close();
