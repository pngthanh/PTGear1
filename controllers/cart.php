<?php
session_start();

require_once __DIR__ . '/../models/connect.php';
require_once __DIR__ . '/../models/product.php';

$database = new Database();
$conn = $database->conn;
$productModel = new Product($conn);

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id && !isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['product_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);
    $variant = $_POST['variant'] ?? 'Mặc định';

    header('Content-Type: application/json');

    if ($action === 'add') {
        if ($productId > 0 && $quantity > 0) {
            if ($user_id) {
                $stmt_check = $conn->prepare("SELECT quantity FROM user_cart WHERE user_id = ? AND product_id = ?");
                $stmt_check->bind_param("ii", $user_id, $productId);
                $stmt_check->execute();
                $result = $stmt_check->get_result();

                if ($result->num_rows > 0) {
                    $existing = $result->fetch_assoc();
                    $new_quantity = $existing['quantity'] + $quantity;
                    $stmt_update = $conn->prepare("UPDATE user_cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
                    $stmt_update->bind_param("iii", $new_quantity, $user_id, $productId);
                    $stmt_update->execute();
                    $stmt_update->close();
                } else {
                    $stmt_insert = $conn->prepare("INSERT INTO user_cart (user_id, product_id, quantity, variant) VALUES (?, ?, ?, ?)");
                    $stmt_insert->bind_param("iiis", $user_id, $productId, $quantity, $variant);
                    $stmt_insert->execute();
                    $stmt_insert->close();
                }
                $stmt_check->close();
                echo json_encode(['success' => true, 'message' => 'Đã thêm vào giỏ hàng']);
            } else {
                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId]['quantity'] += $quantity;
                } else {
                    $_SESSION['cart'][$productId] = ['quantity' => $quantity, 'variant' => $variant];
                }
                echo json_encode(['success' => true, 'message' => 'Đã thêm vào giỏ hàng ']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        }
        exit;
    } elseif ($action === 'update') {
        if ($productId > 0 && $quantity > 0) {
            if ($user_id) {
                $stmt = $conn->prepare("UPDATE user_cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
                $stmt->bind_param("iii", $quantity, $user_id, $productId);
                $stmt->execute();
                $stmt->close();
            } else {
                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId]['quantity'] = $quantity;
                }
            }
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    } elseif ($action === 'remove') {
        if ($productId > 0) {
            if ($user_id) {
                $stmt = $conn->prepare("DELETE FROM user_cart WHERE user_id = ? AND product_id = ?");
                $stmt->bind_param("ii", $user_id, $productId);
                $stmt->execute();
                $stmt->close();
            } else {
                if (isset($_SESSION['cart'][$productId])) {
                    unset($_SESSION['cart'][$productId]);
                }
            }
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }
}

$cartItems = [];

if ($user_id) {
    $stmt = $conn->prepare("SELECT p.id, p.name, p.image, p.price, p.discount_percent, p.stock, uc.quantity, uc.variant 
                           FROM user_cart uc
                           JOIN products p ON uc.product_id = p.id
                           WHERE uc.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $discount = $row['discount_percent'];
        $original_price = floatval($row['price']);
        $newPrice = $original_price;
        if ($discount > 0) {
            $newPrice = $original_price * (100 - $discount) / 100;
        }

        $cartItems[] = [
            'product_id' => $row['id'],
            'name' => $row['name'],
            'image' => $row['image'],
            'price' => $newPrice,
            'original_price' => $original_price,
            'quantity' => intval($row['quantity']),
            'variant' => $row['variant'] ?? 'Mặc định',
            'stock' => intval($row['stock'] ?? 999)
        ];
    }
    $stmt->close();
} else {
    $cart = $_SESSION['cart'];
    foreach ($cart as $productId => $item) {
        $product = $productModel->getProductById($productId);
        if ($product) {
            $discount = $product['discount_percent'];
            $original_price = floatval($product['price']);
            $newPrice = $original_price;
            if ($discount > 0) {
                $newPrice = $original_price * (100 - $discount) / 100;
            }

            $cartItems[] = [
                'product_id' => $productId,
                'name' => $product['name'],
                'image' => $product['image'],
                'price' => $newPrice,
                'original_price' => $original_price,
                'quantity' => intval($item['quantity']),
                'variant' => $item['variant'] ?? 'Mặc định',
                'stock' => intval($product['stock'] ?? 999)
            ];
        }
    }
}
require_once __DIR__ . '/../views/v_cart.php';

$database->close();
