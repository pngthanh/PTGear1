<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

require_once __DIR__ . '/../models/connect.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Order.php';

$db = new Database();
$conn = $db->conn;

$user_id = $_SESSION['user_id'];

$user = User::getUserById($conn, $user_id);
if (!$user) {
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}

$orders = Order::getOrdersByUserId($conn, $user_id);

require_once __DIR__ . '/../views/v_orders.php';

$db->close();
