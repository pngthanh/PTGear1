<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

require_once __DIR__ . '/../models/connect.php';

$database = new Database();
$conn = $database->conn;

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$order = null;

if ($order_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $order = $result->fetch_assoc();
    }
    $stmt->close();
}

// Truyền dữ liệu sang view
require_once __DIR__ . '/../views/v_order_success.php';

$database->close();
