<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

require_once __DIR__ . '/../models/connect.php';
require_once __DIR__ . '/../models/User.php';

$db = new Database();
$conn = $db->conn;

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Xử lý khi người dùng gửi form 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // XỬ LÝ CẬP NHẬT THÔNG TIN CÁ NHÂN
    if ($action === 'update_profile') {
        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        // Kiểm tra email 
        if (User::checkEmailExistsForUpdate($conn, $email, $user_id)) {
            $error = 'Email này đã được sử dụng bởi tài khoản khác!';
        } elseif (User::checkPhoneExistsForUpdate($conn, $phone, $user_id)) {
            $error = 'Số điện thoại này đã được sử dụng bởi tài khoản khác!';
        } else {
            // Gọi hàm model để cập nhật
            if (User::updateProfile($conn, $user_id, $fullname, $email, $phone)) {
                $success = 'Cập nhật thông tin thành công!';
            } else {
                $error = 'Có lỗi xảy ra, không thể cập nhật thông tin.';
            }
        }
    }

    // XỬ LÝ ĐỔI MẬT KHẨU
    elseif ($action === 'change_password') {
        $oldPassword = $_POST['oldPassword'] ?? '';
        $newPassword = $_POST['newPassword'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';

        // Validate 
        if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
            $error = 'Vui lòng điền đầy đủ thông tin mật khẩu!';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'Mật khẩu mới và xác nhận không khớp!';
        } elseif (strlen($newPassword) < 6) {
            $error = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
        } else {
            // Gọi hàm model để đổi mật khẩu
            $result = User::changePassword($conn, $user_id, $oldPassword, $newPassword);

            if ($result === true) {
                $success = 'Đổi mật khẩu thành công!';
            } else {
                $error = $result;
            }
        }
    }
}

// Lấy thông tin người dùng để hiển thị
$user = User::getUserById($conn, $user_id);
if (!$user) {
    // Nếu user bị xóa vì lý do nào đó, đăng xuất
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}

require_once __DIR__ . '/../views/v_profile.php';

$db->close();
