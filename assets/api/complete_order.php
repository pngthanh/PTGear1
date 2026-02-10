<?php
session_start();
header('Content-Type: application/json');

// 1. Kiểm tra đăng nhập cơ bản
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập.']);
    exit;
}

// 2. Lấy dữ liệu
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

// 3. Gọi hàm Model
$result = Order::completeOrder($conn, $order_id, $user_id);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Đã xác nhận nhận hàng thành công!']);
} else {
    $check = $conn->query("SELECT status FROM orders WHERE id = $order_id");
    $row = $check->fetch_assoc();
    $status = $row ? $row['status'] : 'Không tồn tại';
    
    http_response_code(403);
    echo json_encode([
        'success' => false, 
        'message' => "Lỗi: Đơn hàng đang ở trạng thái '$status' (Cần là 'shipping')"
    ]);
}

$db->close();
?>