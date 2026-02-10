<?php

session_start();

require_once __DIR__ . '/../models/connect.php';
require_once __DIR__ . '/../models/User.php';

$database = new Database();
$conn = $database->conn;

$error = '';
$success = '';
$clear_username = false;
$clear_email = false;
$clear_password = false;
$post_data = ['username' => '', 'email' => '', 'password' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // giữ lại giá trị post để điền lại form
    $post_data = ['username' => $username, 'email' => $email, 'password' => $password];

    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Vui lòng nhập đầy đủ thông tin!';
        $clear_username = empty($username);
        $clear_email = empty($email);
        $clear_password = empty($password);
    }
    // Ktra username
    elseif (User::checkUserExists($conn, $username)) {
        $error = 'Tài khoản đã được sử dụng';
        $clear_username = true;
    }
    // Ktra email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email sai định dạng';
        $clear_email = true;
    } elseif (User::checkEmailExists($conn, $email)) {
        $error = 'Email đã được sử dụng';
        $clear_email = true;
    }
    // Ktra password
    elseif (strlen($password) < 6 || !preg_match('/\d/', $password)) {
        $error = 'Mật khẩu phải trên 6 ký tự và có ít nhất 1 số';
        $clear_password = true;
    }
    // kh có lỗi -> Đăng ký
    else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $newUserId = User::createUser($conn, $username, $email, $hashed_password);

        if ($newUserId) {
            $success = 'Đăng ký thành công! Đang chuyển hướng...';
            // kh set session ngay, bắt đăng nhập lại
            echo "<script>
                    setTimeout(function(){
                        window.location.href = 'index.php?page=login';
                    }, 3000);
                  </script>";
        } else {
            $error = 'Đăng ký thất bại. Vui lòng thử lại!';
        }
    }
}

require_once __DIR__ . '/../views/v_register.php';

$database->close();
