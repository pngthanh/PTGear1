<?php
session_start();
header('Content-Type: application/json');

// ktra Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền thực hiện việc này.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$order_id = $data['order_id'] ?? null;

if (!$order_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Thiếu ID đơn hàng.']);
    exit;
}

require_once __DIR__ . '/../../models/connect.php';
require_once __DIR__ . '/../../models/Order.php';

$db = new Database();
$conn = $db->conn;

// gọi hàm model
$result = Order::approveOrder($conn, $order_id);

if ($result['success']) {
    echo json_encode($result);
} else {
    http_response_code(400);
    echo json_encode($result);
}

$db->close();
