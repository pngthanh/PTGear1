<?php
require_once __DIR__ . '/../../models/connect.php';
require_once __DIR__ . '/../../models/User.php';

$db = new Database();
$conn = $db->conn;

$id = $_POST['id'] ?? 0;
$status = $_POST['status'] ?? 'active';

if ($id > 0 && User::toggleUserStatus($conn, $id, $status)) {
    echo "success";
} else {
    echo "error";
}

$db->close();
