<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để thực hiện việc này.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ.']);
    exit;
}

// Lấy dữ liệu 
$data = json_decode(file_get_contents('php://input'), true);
$order_id = $data['order_id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$order_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Thiếu ID đơn hàng.']);
    exit;
}

require_once __DIR__ . '/../../models/connect.php';
require_once __DIR__ . '/../../models/Order.php';

$db = new Database();
$conn = $db->conn;

// Gọi hàm model để hủy đơn hàng
$result = Order::cancelOrderById($conn, $order_id, $user_id);

if ($result) {
    // Hủy thành công
    echo json_encode(['success' => true, 'message' => 'Đã hủy đơn hàng thành công.']);
} else {
    // Hủy thất bại
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Không thể hủy đơn hàng này.']);
}

$db->close();
