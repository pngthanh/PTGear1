<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php?page=home");
    exit;
}

require_once __DIR__ . '/../models/connect.php';
require_once __DIR__ . '/../models/User.php';

$db = new Database();
$conn = $db->conn;

$users = User::getAllUsers($conn);

$pageTitle = "Quản lý Tài khoản";
$page = 'admin_users';

require_once __DIR__ . '/../views/v_admin_users.php';

$db->close();
