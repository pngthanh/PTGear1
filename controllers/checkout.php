<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

require_once __DIR__ . '/../models/connect.php';
require_once __DIR__ . '/../models/product.php';
require_once __DIR__ . '/../models/user.php';

$database = new Database();
$conn = $database->conn;
$productModel = new Product($conn);

$user_id = $_SESSION['user_id'];
$error = '';
$cartItems = [];
$subtotal = 0;
$idList = [];


$user = User::getUserById($conn, $user_id);
if (!$user) {
    header('Location: index.php?page=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    if (isset($_GET['buy_now_id']) && !empty($_GET['buy_now_id'])) {
        $productId = intval($_GET['buy_now_id']);
        $quantity = intval($_GET['quantity'] ?? 1);
        $product = $productModel->getProductById($productId);

        if ($product && $quantity > 0) {
            $idList[] = $productId;
            $discount = $product['discount_percent'];
            $price = ($discount > 0) ? (floatval($product['price']) * (100 - $discount) / 100) : floatval($product['price']);
            $itemTotal = $price * $quantity;
            $subtotal += $itemTotal;

            $cartItems[] = [
                'product_id' => $product['id'],
                'name' => $product['name'],
                'image' => $product['image'],
                'price' => $price,
                'quantity' => $quantity,
                'variant' => 'Mặc định',
                'total' => $itemTotal
            ];
        } else {
            header('Location: index.php?page=home');
            exit;
        }
    } elseif (isset($_GET['selected_ids']) && !empty($_GET['selected_ids'])) {
        $selected_ids_str = $_GET['selected_ids'];
        $idList = array_map('intval', explode(',', $selected_ids_str));

        if (empty($idList)) {
            header('Location: index.php?page=cart');
            exit;
        }

        $placeholders = implode(',', array_fill(0, count($idList), '?'));
        // Thêm user_id vào danh sách
        $params = $idList;
        $params[] = $user_id;
        $types = str_repeat('i', count($idList)) . 'i';

        $stmt_cart = $conn->prepare("SELECT p.id, p.name, p.image, p.price, p.discount_percent, uc.quantity, uc.variant 
                                   FROM user_cart uc JOIN products p ON uc.product_id = p.id
                                   WHERE uc.product_id IN ($placeholders) AND uc.user_id = ?");
        $stmt_cart->bind_param($types, ...$params);
        $stmt_cart->execute();
        $result_cart = $stmt_cart->get_result();

        if ($result_cart->num_rows === 0) {
            header('Location: index.php?page=cart');
            exit;
        }

        while ($product = $result_cart->fetch_assoc()) {
            $discount = $product['discount_percent'];
            $price = ($discount > 0) ? (floatval($product['price']) * (100 - $discount) / 100) : floatval($product['price']);
            $quantity = intval($product['quantity']);
            $itemTotal = $price * $quantity;
            $subtotal += $itemTotal;

            $cartItems[] = [
                'product_id' => $product['id'],
                'name' => $product['name'],
                'image' => $product['image'],
                'price' => $price,
                'quantity' => $quantity,
                'variant' => $product['variant'] ?? 'Mặc định',
                'total' => $itemTotal
            ];
        }
        $stmt_cart->close();
    } else {
        // kh có gì để thanh toán
        header('Location: index.php?page=cart');
        exit;
    }
}

// Tính toán tổng tiền
$shippingFee = 30000;
$total = $subtotal + $shippingFee;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient_name = trim($_POST['recipient_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $shipping_address = trim($_POST['shipping_address'] ?? '');

    if (empty($recipient_name)) $error = 'Vui lòng nhập tên người nhận!';
    elseif (empty($phone)) $error = 'Vui lòng nhập số điện thoại!';
    elseif (!preg_match('/^[0-9]{10,11}$/', $phone)) $error = 'Số điện thoại không hợp lệ!';
    elseif (empty($shipping_address)) $error = 'Vui lòng nhập địa chỉ giao hàng!';


    if (empty($error)) {
        $clearCart = false; // Flag để biết có xóa giỏ hàng hay không

        if (isset($_POST['buy_now_id']) && !empty($_POST['buy_now_id'])) {
            $productId = intval($_POST['buy_now_id']);
            $quantity = intval($_POST['buy_now_quantity'] ?? 1);
            $product = $productModel->getProductById($productId);

            if ($product) {
                $idList[] = $productId; // Thêm ID vào danh sách
                $discount = $product['discount_percent'];
                $price = ($discount > 0) ? (floatval($product['price']) * (100 - $discount) / 100) : floatval($product['price']);
                $subtotal = $price * $quantity;
                $total = $subtotal + $shippingFee;
                $cartItems = [['product_id' => $productId, 'price' => $price, 'quantity' => $quantity]];
            } else {
                $error = 'Sản phẩm "Mua ngay" không hợp lệ!';
            }
        } elseif (isset($_POST['selected_ids']) && !empty($_POST['selected_ids'])) {
            $selected_ids_str = $_POST['selected_ids'];
            $idList = array_map('intval', explode(',', $selected_ids_str));

            if (empty($idList)) $error = 'Không có sản phẩm nào được chọn!';
            else $clearCart = true; // Sẽ xóa các sản phẩm này khỏi giỏ

            $placeholders = implode(',', array_fill(0, count($idList), '?'));
            $params = $idList;
            $params[] = $user_id;
            $types = str_repeat('i', count($idList)) . 'i';

            $stmt_cart = $conn->prepare("SELECT p.id, p.price, p.discount_percent, uc.quantity 
                                       FROM user_cart uc JOIN products p ON uc.product_id = p.id
                                       WHERE uc.product_id IN ($placeholders) AND uc.user_id = ?");
            $stmt_cart->bind_param($types, ...$params);
            $stmt_cart->execute();
            $result_cart = $stmt_cart->get_result();

            if ($result_cart->num_rows === 0) $error = 'Sản phẩm trong giỏ hàng không hợp lệ!';

            $subtotal = 0;
            $cartItems = [];
            while ($product = $result_cart->fetch_assoc()) {
                $discount = $product['discount_percent'];
                $price = ($discount > 0) ? (floatval($product['price']) * (100 - $discount) / 100) : floatval($product['price']);
                $quantity = intval($product['quantity']);
                $subtotal += $price * $quantity;
                $cartItems[] = ['product_id' => $product['id'], 'price' => $price, 'quantity' => $quantity];
            }
            $total = $subtotal + $shippingFee;
            $stmt_cart->close();
        } else {
            $error = 'Không có thông tin thanh toán. Vui lòng thử lại.';
        }

        if (empty($error)) {
            $order_code = 'PTG' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $conn->begin_transaction();
            try {
                // LƯU ĐƠN HÀNG (orders)
                $stmt_order = $conn->prepare("INSERT INTO orders (user_id, recipient_name, order_code, total_price, shipping_address, phone, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
                $stmt_order->bind_param("isssss", $user_id, $recipient_name, $order_code, $total, $shipping_address, $phone);
                $stmt_order->execute();
                $order_id = $conn->insert_id;
                $stmt_order->close();

                // LƯU CHI TIẾT ĐƠN HÀNG (order_details)
                $detailStmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                foreach ($cartItems as $item) {
                    $detailStmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
                    if (!$detailStmt->execute()) throw new Exception('Lỗi khi lưu chi tiết đơn hàng.');
                }
                $detailStmt->close();

                // XÓA GIỎ HÀNG
                if ($clearCart && !empty($idList)) {
                    $placeholders_del = implode(',', array_fill(0, count($idList), '?'));
                    $params_del = $idList;
                    $params_del[] = $user_id;
                    $types_del = str_repeat('i', count($idList)) . 'i';

                    $stmt_clear_cart = $conn->prepare("DELETE FROM user_cart WHERE product_id IN ($placeholders_del) AND user_id = ?");
                    $stmt_clear_cart->bind_param($types_del, ...$params_del);
                    $stmt_clear_cart->execute();
                    $stmt_clear_cart->close();
                }

                $conn->commit();
                header('Location: index.php?page=order-success&order_id=' . $order_id);
                exit;
            } catch (Exception $e) {
                $conn->rollback();
                $error = $e->getMessage();
            }
        }
    }

    // Tải lại $cartItems nếu có lỗi POST
    if (!empty($error) && empty($cartItems)) {
        if (isset($_POST['buy_now_id'])) {
            $productId = intval($_POST['buy_now_id']);
            $quantity = intval($_POST['buy_now_quantity'] ?? 1);
            $product = $productModel->getProductById($productId);
            if ($product) {
                $discount = $product['discount_percent'];
                $price = ($discount > 0) ? (floatval($product['price']) * (100 - $discount) / 100) : floatval($product['price']);
                $cartItems[] = ['product_id' => $product['id'], 'name' => $product['name'], 'image' => $product['image'], 'price' => $price, 'quantity' => $quantity, 'variant' => 'Mặc định'];
            }
        } elseif (isset($_POST['selected_ids'])) {
            $idList = array_map('intval', explode(',', $_POST['selected_ids']));
            $placeholders = implode(',', array_fill(0, count($idList), '?'));
            $params = $idList;
            $params[] = $user_id;
            $types = str_repeat('i', count($idList)) . 'i';
            $stmt_cart = $conn->prepare("SELECT p.id, p.name, p.image, p.price, p.discount_percent, uc.quantity, uc.variant FROM user_cart uc JOIN products p ON uc.product_id = p.id WHERE uc.product_id IN ($placeholders) AND uc.user_id = ?");
            $stmt_cart->bind_param($types, ...$params);
            $stmt_cart->execute();
            $result_cart = $stmt_cart->get_result();
            while ($product = $result_cart->fetch_assoc()) {
                $discount = $product['discount_percent'];
                $price = ($discount > 0) ? (floatval($product['price']) * (100 - $discount) / 100) : floatval($product['price']);
                $cartItems[] = ['product_id' => $product['id'], 'name' => $product['name'], 'image' => $product['image'], 'price' => $price, 'quantity' => intval($product['quantity']), 'variant' => $product['variant'] ?? 'Mặc định'];
            }
            $stmt_cart->close();
        }
    }
}
require_once __DIR__ . '/../views/v_checkout.php';

$database->close();
