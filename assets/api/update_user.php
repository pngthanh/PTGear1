<?php
require_once __DIR__ . '/../../models/connect.php';
require_once __DIR__ . '/../../models/User.php';

// Khởi tạo kết nối
$db = new Database();
$conn = $db->conn;

// Lấy dữ liệu từ POST
$id = $_POST['id'] ?? 0;
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$role = $_POST['role'] ?? 'user';
$password = $_POST['password'] ?? '';

// Kiểm tra email (gọi Model)
if (User::checkEmailExistsForUpdate($conn, $email, $id)) {
    echo "Email đã được sử dụng bởi tài khoản khác";
    $db->close();
    exit;
}

// Kiểm tra SĐT (gọi Model)
if (User::checkPhoneExistsForUpdate($conn, $phone, $id)) {
    echo "Số điện thoại đã được sử dụng bởi tài khoản khác";
    $db->close();
    exit;
}

// Xử lý cập nhật (gọi Model)
if (User::updateUserByAdmin($conn, $id, $email, $phone, $role, $password)) {
    echo "success";
} else {
    error_log("SQL Error: " . $conn->error);
    echo "error: " . $conn->error;
}

$db->close();
