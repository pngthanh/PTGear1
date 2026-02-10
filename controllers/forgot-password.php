<?php
session_start();

require_once __DIR__ . '/../models/connect.php';
require_once __DIR__ . '/../models/User.php';


$db = new Database();
$conn = $db->conn;

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $message = 'Vui lòng nhập email!';
        $message_type = 'error';
    } else {
        if (User::checkEmailExists($conn, $email)) {
            // logic gửi mail t chưa làm
            $message = 'Nếu email tồn tại, một link khôi phục đã được gửi!';
            $message_type = 'success';
        } else {
            $message = 'Không tìm thấy email!';
            $message_type = 'error';
        }
    }
}

require_once __DIR__ . '/../views/v_forgot-password.php';

$db->close();
