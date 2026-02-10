<?php
session_start();

require_once __DIR__ . '/../models/connect.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/Product.php';

$database = new Database();
$conn = $database->conn;

$error = '';
function mergeSessionCartToDb($conn, $user_id, $sessionCart)
{
    if (!empty($sessionCart) && is_array($sessionCart)) {
        $stmt_check = $conn->prepare("SELECT quantity FROM user_cart WHERE user_id = ? AND product_id = ?");
        $stmt_update = $conn->prepare("UPDATE user_cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt_insert = $conn->prepare("INSERT INTO user_cart (user_id, product_id, quantity, variant) VALUES (?, ?, ?, ?)");

        foreach ($sessionCart as $product_id => $item) {
            $quantity = $item['quantity'];
            $variant = $item['variant'] ?? 'Mặc định';

            $stmt_check->bind_param("ii", $user_id, $product_id);
            $stmt_check->execute();
            $result = $stmt_check->get_result();

            if ($result->num_rows > 0) {
                // Đã có -> Cập nhật
                $existing = $result->fetch_assoc();
                $new_quantity = $existing['quantity'] + $quantity;
                $stmt_update->bind_param("iii", $new_quantity, $user_id, $product_id);
                $stmt_update->execute();
            } else {
                // Chưa có -> Thêm mới
                $stmt_insert->bind_param("iiis", $user_id, $product_id, $quantity, $variant);
                $stmt_insert->execute();
            }
        }
        $stmt_check->close();
        $stmt_update->close();
        $stmt_insert->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Vui lòng nhập đầy đủ tài khoản và mật khẩu!';
    } else {
        $user = User::findUserByUsername($conn, $username);

        if ($user === null) {
            $error = 'Tài khoản không tồn tại!';
        } elseif (!password_verify($password, $user['password'])) {
            $error = 'Mật khẩu không chính xác!';
        } elseif ($user['status'] === 'inactive') {
            $error = 'Tài khoản đã bị khóa!';
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];

            if (!empty($_SESSION['cart'])) {
                mergeSessionCartToDb($conn, $user['id'], $_SESSION['cart']);
                unset($_SESSION['cart']);
            }

            if ($user['role'] === 'admin') {
                header('Location: index.php?page=admin_users');
                exit;
            } else {
                header('Location: index.php?page=home');
                exit;
            }
        }
    }
}

require_once __DIR__ . '/../views/v_login.php';

$database->close();
