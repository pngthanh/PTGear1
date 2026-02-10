<?php
require_once __DIR__ . '/../../models/connect.php';
require_once __DIR__ . '/../../models/User.php';

$db = new Database();
$conn = $db->conn;

$id = $_POST['id'] ?? 0;

if ($id > 0 && User::deleteUser($conn, $id)) {
    echo "success";
} else {
    echo "error";
}

$db->close();
