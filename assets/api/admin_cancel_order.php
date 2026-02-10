<?php
session_start();
header('Content-Type: application/json');

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

// gọi hàm model mới
$result = Order::adminCancelOrder($conn, $order_id);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Đã hủy đơn hàng thành công.']);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Không thể hủy đơn hàng này.']);
}

$db->close();
