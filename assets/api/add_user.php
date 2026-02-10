<?php

require_once __DIR__ . '/../../models/connect.php';
require_once __DIR__ . '/../../models/User.php';

// Khởi tạo kết nối
$db = new Database();
$conn = $db->conn;

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'user';

// Kiểm tra định dạng
if (empty($username) || empty($email) || empty($password)) {
    echo "Vui lòng nhập đầy đủ thông tin";
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Email sai định dạng";
    exit;
}
if (strlen($password) < 6 || !preg_match("/[0-9]/", $password)) {
    echo "Mật khẩu phải trên 6 ký tự và có ít nhất 1 số";
    exit;
}

// Kiểm tra username đã tồn tại
if (User::checkUserExists($conn, $username)) {
    echo "Tài khoản đã được dùng";
    exit;
}

// Kiểm tra email đã tồn tại
if (User::checkEmailExists($conn, $email)) {
    echo "Email đã được dùng";
    exit;
}

// Thêm tài khoản
if (User::createUserByAdmin($conn, $username, $email, $password, $role)) {
    echo "success";
} else {
    error_log("SQL Error: " . $conn->error);
    echo "Lỗi khi thêm tài khoản";
}

$db->close();
