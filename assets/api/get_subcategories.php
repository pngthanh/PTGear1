<?php
require_once __DIR__ . '/../../models/connect.php';
require_once __DIR__ . '/../../models/Product.php';

$categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

if ($categoryId > 0) {
    $db = new Database();
    $conn = $db->conn;
    $productModel = new Product($conn);

    $result = $productModel->getSubcategoriesByCategoryId($categoryId);

    $subcategories = [];
    while ($row = $result->fetch_assoc()) {
        $subcategories[] = $row;
    }

    $db->close();
    header('Content-Type: application/json');
    echo json_encode($subcategories);
} else {
    echo json_encode([]);
}
