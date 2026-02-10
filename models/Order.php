<?php
class Order
{
    // lấy hết thông tin đơn hàng của user
    public static function getOrdersByUserId($conn, $user_id)
    {
        $orders = [];

        // lấy hết đơn hàng của user
        $stmt_orders = $conn->prepare("
            SELECT id, order_code, total_price, status 
            FROM orders 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt_orders->bind_param("i", $user_id);
        $stmt_orders->execute();
        $result_orders = $stmt_orders->get_result();

        if ($result_orders->num_rows === 0) {
            return [];
        }

        // lấy chi tiết đơn hàng
        $stmt_details = $conn->prepare("
            SELECT od.quantity, od.price, p.name, p.image 
            FROM order_details od
            JOIN products p ON od.product_id = p.id
            WHERE od.order_id = ?
        ");

        // lặp qua từng đơn hàng để lấy chi tiết
        while ($order = $result_orders->fetch_assoc()) {
            $order_id = $order['id'];
            $order_data = [
                'id' => $order['id'],
                'order_code' => $order['order_code'],
                'total_price' => $order['total_price'],
                'status' => $order['status'],
                'products' => []
            ];

            // lấy các spham cho đơn hàng
            $stmt_details->bind_param("i", $order_id);
            $stmt_details->execute();
            $result_details = $stmt_details->get_result();

            while ($product = $result_details->fetch_assoc()) {
                $order_data['products'][] = $product;
            }

            $orders[] = $order_data;
        }

        $stmt_orders->close();
        $stmt_details->close();

        return $orders;
    }

    // hủy đơn
    public static function cancelOrderById($conn, $order_id, $user_id)
    {

        $stmt = $conn->prepare("
            UPDATE orders 
            SET status = 'canceled' 
            WHERE id = ? 
              AND user_id = ? 
              AND status = 'pending'
        ");

        $stmt->bind_param("ii", $order_id, $user_id);
        $stmt->execute();

        $affected_rows = $stmt->affected_rows;
        $stmt->close();

        return $affected_rows > 0;
    }

    // lấy chi tiết bằng id
    public static function getOrderDetailsById($conn, $order_id, $user_id)
    {
        // lấy thông tin đơn hàng chính
        $stmt_order = $conn->prepare("
            SELECT * FROM orders 
            WHERE id = ? AND user_id = ?
        ");
        $stmt_order->bind_param("ii", $order_id, $user_id);
        $stmt_order->execute();
        $result_order = $stmt_order->get_result();

        if ($result_order->num_rows === 0) {
            $stmt_order->close();
            return null; // kh tìm thấy đơn hàng hoặc kh đúng chủ
        }

        $order_data = $result_order->fetch_assoc();
        $order_data['products'] = [];
        $stmt_order->close();

        // lấy chi tiết các spham trong đơn hàng
        $stmt_details = $conn->prepare("
            SELECT od.quantity, od.price, p.name, p.image 
            FROM order_details od
            JOIN products p ON od.product_id = p.id
            WHERE od.order_id = ?
        ");
        $stmt_details->bind_param("i", $order_id);
        $stmt_details->execute();
        $result_details = $stmt_details->get_result();

        while ($product = $result_details->fetch_assoc()) {
            $order_data['products'][] = $product;
        }

        $stmt_details->close();

        return $order_data;
    }


    // lấy hét đơn
    public static function getAllOrders($conn)
    {
        $sql = "SELECT 
                    id, 
                    order_code, 
                    recipient_name, 
                    phone, 
                    total_price, 
                    status, 
                    created_at 
                FROM orders 
                ORDER BY created_at DESC";

        return $conn->query($sql);
    }

    // admin duyệt
    public static function approveOrder($conn, $order_id)
    {
        $conn->begin_transaction();

        try {
            // lấy thông tin đơn hàng
            $stmt_order = $conn->prepare("SELECT status FROM orders WHERE id = ? FOR UPDATE");
            $stmt_order->bind_param("i", $order_id);
            $stmt_order->execute();
            $order_status = $stmt_order->get_result()->fetch_assoc()['status'];
            $stmt_order->close();

            if ($order_status !== 'pending') {
                $conn->rollback();
                return ['success' => false, 'message' => 'Đơn hàng này không ở trạng thái chờ.'];
            }

            // lấy chi tiết spham trong đơn
            $stmt_details = $conn->prepare("SELECT product_id, quantity FROM order_details WHERE order_id = ?");
            $stmt_details->bind_param("i", $order_id);
            $stmt_details->execute();
            $details = $stmt_details->get_result();
            $products_to_update = [];
            while ($row = $details->fetch_assoc()) {
                $products_to_update[] = $row;
            }
            $stmt_details->close();

            if (empty($products_to_update)) {
                $conn->rollback();
                return ['success' => false, 'message' => 'Đơn hàng không có sản phẩm.'];
            }

            // kiểm tra và cập nhật tồn kho
            $stmt_check_stock = $conn->prepare("SELECT stock FROM products WHERE id = ? FOR UPDATE");
            $stmt_update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

            foreach ($products_to_update as $item) {
                // Khóa hàng (FOR UPDATE) và kiểm tra
                $stmt_check_stock->bind_param("i", $item['product_id']);
                $stmt_check_stock->execute();
                $stock = $stmt_check_stock->get_result()->fetch_assoc()['stock'];

                if ($stock < $item['quantity']) {
                    // nếu hết hàng, hủy bỏ
                    $conn->rollback();
                    return ['success' => false, 'message' => 'Không đủ số lượng tồn kho cho sản phẩm (ID: ' . $item['product_id'] . ').'];
                }

                // trừ tồn kho
                $stmt_update_stock->bind_param("ii", $item['quantity'], $item['product_id']);
                $stmt_update_stock->execute();
            }

            $stmt_check_stock->close();
            $stmt_update_stock->close();

            // cập nhật trạng thái đơn hàng
            $stmt_update_order = $conn->prepare("UPDATE orders SET status = 'shipping' WHERE id = ?");
            $stmt_update_order->bind_param("i", $order_id);
            $stmt_update_order->execute();
            $stmt_update_order->close();

            // thành công
            $conn->commit();
            return ['success' => true, 'message' => 'Duyệt đơn hàng thành công.'];
        } catch (Exception $e) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    // user xác nhận
public static function completeOrder($conn, $order_id, $user_id)
{
    $stmt = $conn->prepare("
        UPDATE orders 
        SET status = 'completed' 
        WHERE id = ? 
          AND status = 'shipping'
    ");

    $stmt->bind_param("i", $order_id);
    
    $stmt->execute();

    $affected_rows = $stmt->affected_rows;
    $stmt->close();

    return $affected_rows > 0;
}

    // hủy đơn (admin)
    public static function adminCancelOrder($conn, $order_id)
    {
        $stmt = $conn->prepare("
            UPDATE orders 
            SET status = 'canceled' 
            WHERE id = ? 
              AND status = 'pending'
        ");

        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        $affected_rows = $stmt->affected_rows;
        $stmt->close();

        return $affected_rows > 0;
    }

    // lấy chi tiết đơn cho admin
    public static function adminGetOrderDetailsById($conn, $order_id)
    {
        // lấy thông tin đơn hàng chính
        $stmt_order = $conn->prepare("
            SELECT * FROM orders 
            WHERE id = ?
        ");
        $stmt_order->bind_param("i", $order_id);
        $stmt_order->execute();
        $result_order = $stmt_order->get_result();

        if ($result_order->num_rows === 0) {
            $stmt_order->close();
            return null; // kh tìm thấy đơn hàng
        }

        $order_data = $result_order->fetch_assoc();
        $order_data['products'] = [];
        $stmt_order->close();

        // lấy chi tiết các spham trong đơn hàng
        $stmt_details = $conn->prepare("
            SELECT od.quantity, od.price, p.name, p.image 
            FROM order_details od
            JOIN products p ON od.product_id = p.id
            WHERE od.order_id = ?
        ");
        $stmt_details->bind_param("i", $order_id);
        $stmt_details->execute();
        $result_details = $stmt_details->get_result();

        while ($product = $result_details->fetch_assoc()) {
            $order_data['products'][] = $product;
        }

        $stmt_details->close();

        return $order_data;
    }
}
