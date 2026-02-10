<?php
$status_map = [
    'pending' => 'Chờ Xử Lý',
    'confirmed' => 'Đã Xác Nhận',
    'shipping' => 'Đang Giao',
    'completed' => 'Đã Giao',
    'canceled' => 'Đã Hủy'
];
$current_status = $order['status'];
$current_status_text = $status_map[$current_status] ?? ucfirst($current_status);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/HeaderFooter.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/admin_order_detail.css">
    <title>Chi Tiết Đơn Hàng - Admin</title>
</head>

<body>
    <div class="page-wrapper">
        <?php require_once __DIR__ . '/layouts/header.php'; ?>

        <div class="admin-container">

            <?php require_once __DIR__ . '/layouts/sidebar_admin.php'; ?>

            <div class="main-content">
                <div class="detail-header">
                    <a href="index.php?page=admin_orders" class="back-link"><i class="fa fa-arrow-left"></i> Quay lại</a>
                    <h1 class="detail-title">Chi Tiết Đơn Hàng #<?= htmlspecialchars($order['order_code']) ?></h1>
                    <span class="status-badge status-<?= htmlspecialchars($current_status) ?>">
                        <?= htmlspecialchars($current_status_text) ?>
                    </span>

                    <div class="header-actions">
                        <?php if ($current_status == 'pending'): ?>
                            <button class="btn-action-header btn-approve" id="btn-approve-detail">
                                <i class="fa fa-check"></i> Duyệt Đơn Hàng
                            </button>
                            <button class="btn-action-header btn-cancel-admin" id="btn-cancel-detail">
                                <i class="fa fa-times"></i> Hủy Đơn Hàng
                            </button>
                        <?php else: ?>
                            <button class="btn-action-header" disabled>
                                <i class="fa fa-check"></i> Duyệt Đơn Hàng
                            </button>
                            <button class="btn-action-header" disabled>
                                <i class="fa fa-times"></i> Hủy Đơn Hàng
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="order-detail-layout">

                    <div class="order-detail-main">
                        <div class="detail-card">
                            <h2 class="card-title">Thông Tin Giao Hàng</h2>
                            <div class="info-group">
                                <label>Khách hàng:</label>
                                <span><?= htmlspecialchars($order['recipient_name']) ?></span>
                            </div>
                            <div class="info-group">
                                <label>Số điện thoại:</label>
                                <span><?= htmlspecialchars($order['phone']) ?></span>
                            </div>
                            <div class="info-group">
                                <label>Địa chỉ:</label>
                                <span><?= htmlspecialchars($order['shipping_address']) ?></span>
                            </div>
                        </div>

                        <div class="detail-card">
                            <h2 class="card-title">Sản Phẩm Đã Đặt</h2>
                            <div class="product-list">
                                <?php foreach ($order['products'] as $product): ?>
                                    <div class="product-item-detail">
                                        <img src="assets/<?= htmlspecialchars($product['image'] ?? 'img/placeholder.png') ?>" alt="" class="product-image">
                                        <div class="product-info">
                                            <h4 class="product-name"><?= htmlspecialchars($product['name']) ?></h4>
                                            <span class="product-quantity">Số lượng: <?= htmlspecialchars($product['quantity']) ?></span>
                                        </div>
                                        <div class="product-pricing">
                                            <span class="product-price"><?= number_format($product['price'], 0, ',', '.') ?>đ</span>
                                            <span class="product-subtotal"><?= number_format($product['price'] * $product['quantity'], 0, ',', '.') ?>đ</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="order-detail-summary">
                        <div class="summary-card">
                            <h2 class="card-title">Tóm Tắt Đơn Hàng</h2>
                            <div class="summary-row total">
                                <span>Tổng cộng:</span>
                                <strong><?= number_format($order['total_price'], 0, ',', '.') ?>đ</strong>
                            </div>
                        </div>
                        <div class="summary-card">
                            <h2 class="card-title">Phương Thức Thanh Toán</h2>
                            <div class="info-group">
                                <span>Thanh toán khi nhận hàng (COD)</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php require_once __DIR__ . '/layouts/footer.php'; ?>

        <div id="toast" class="toast hidden"></div>
    </div>

    <script>
        // truyền id sang js để api sài
        const CURRENT_ORDER_ID = <?= (int)$order['id'] ?>;
    </script>
    <script src="assets/js/admin_order_detail.js"></script>
    <script src="assets/js/headerFooter.js"></script>
    <script src="assets/js/toast.js"></script>
</body>

</html>