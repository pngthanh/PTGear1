<?php
// Ánh xạ
$status_map = [
    'pending' => 'Chờ Xử Lý',
    'confirmed' => 'Đã Xác Nhận',
    'shipping' => 'Đang Giao Hàng',
    'completed' => 'Đã Giao',
    'canceled' => 'Đã Hủy'
];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/HeaderFooter.css">
    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="stylesheet" href="assets/css/orders.css">
    <title>Đơn Hàng Của Tôi - PTGear</title>
</head>

<body>
    <div class="page-wrapper">
        <!-- Header -->
        <?php require_once __DIR__ . '/layouts/header.php'; ?>

        <!-- Đơn Hàng Của Tôi -->
        <div class="content">
            <div class="profile-container">
                <div class="profile-content">
                    <!-- Navigation Menu-->
                    <aside class="profile-sidebar">
                        <!-- User Info -->
                        <div class="sidebar-user-info">
                            <i class="fa fa-user-circle-o user-icon"></i>
                            <h3 class="user-name"><?= htmlspecialchars($user['fullname'] ?? $user['username']) ?></h3>
                        </div>

                        <!-- Navigation Menu -->
                        <nav class="sidebar-menu">
                            <a href="index.php?page=profile" class="menu-item">
                                <i class="fa fa-user"></i>
                                <span>Tài khoản của tôi</span>
                            </a>
                            <a href="index.php?page=orders" class="menu-item active">
                                <i class="fa fa-shopping-bag"></i>
                                <span>Đơn hàng của tôi</span>
                            </a>
                            <a href="index.php?page=logout" class="menu-item">
                                <i class="fa fa-sign-out"></i>
                                <span>Đăng xuất</span>
                            </a>
                        </nav>
                    </aside>

                    <!-- Danh sách đơn hàng -->
                    <div class="profile-main">
                        <!-- Page Header -->
                        <div class="profile-header">
                            <h1 class="profile-title">Đơn Hàng Của Tôi</h1>
                        </div>

                        <!-- Order Tabs -->
                        <nav class="order-tabs">
                            <button class="tab-item active" data-status="all">Tất cả</button>
                            <button class="tab-item" data-status="pending">Chờ xử lý</button>
                            <button class="tab-item" data-status="shipping">Đang giao</button>
                            <button class="tab-item" data-status="completed">Đã giao</button>
                            <button class="tab-item" data-status="canceled">Đã hủy</button>
                        </nav>

                        <!-- Order List -->
                        <div class="order-list">
                            <?php if (empty($orders)): ?>
                                <p class="empty-orders">Bạn chưa có đơn hàng nào.</p>
                            <?php else: ?>
                                <?php foreach ($orders as $order): ?>
                                    <article class="order-card" data-status="<?= htmlspecialchars($order['status']) ?>">
                                        <!-- Order Header -->
                                        <div class="order-header">
                                            <span class="order-code">Mã ĐH: #<?= htmlspecialchars($order['order_code']) ?></span>
                                            <span
                                                class="status-badge status-<?= htmlspecialchars($order['status']) ?>">
                                                <?= htmlspecialchars($status_map[$order['status']] ?? ucfirst($order['status'])) ?>
                                            </span>
                                        </div>

                                        <!-- Order Body  -->
                                        <div class="order-body">
                                            <?php foreach ($order['products'] as $product): ?>
                                                <div class="product-item">
                                                    <img
                                                        src="assets/<?= htmlspecialchars($product['image'] ?? 'img/placeholder.png') ?>"
                                                        alt="<?= htmlspecialchars($product['name']) ?>"
                                                        class="product-image"
                                                        onerror="this.src='https://placehold.co/100x100/e0e0e0/999?text=PTGear'">
                                                    <div class="product-info">
                                                        <h4 class="product-name"><?= htmlspecialchars($product['name']) ?></h4>
                                                        <span class="product-quantity">x <?= htmlspecialchars($product['quantity']) ?></span>
                                                    </div>
                                                    <span
                                                        class="product-price"><?= number_format($product['price'], 0, ',', '.') ?>đ</span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>

                                        <!-- Order Footer -->
                                        <div class="order-footer">
                                            <div class="total-price">
                                                <span>Tổng tiền:</span>
                                                <strong><?= number_format($order['total_price'], 0, ',', '.') ?>đ</strong>
                                            </div>
                                            <div class="order-actions">
                                                <?php if ($order['status'] == 'completed'): ?>
                                                    <button class="btn-action btn-review">Đánh Giá</button>

                                                <?php elseif ($order['status'] == 'shipping'): ?>
                                                    <button
                                                        class="btn-action btn-primary btn-complete"
                                                        data-order-id="<?= htmlspecialchars($order['id']) ?>">
                                                        Đã nhận được hàng
                                                    </button>

                                                <?php elseif ($order['status'] == 'pending'): ?>
                                                    <button
                                                        class="btn-action btn-cancel"
                                                        data-order-id="<?= htmlspecialchars($order['id']) ?>">Hủy Đơn</button>

                                                <?php elseif ($order['status'] == 'canceled'): ?>
                                                    <button class="btn-action btn-reorder" disabled>Đã Hủy</button>

                                                <?php endif; ?>

                                                <a href="index.php?page=order_detail&id=<?= htmlspecialchars($order['id']) ?>" class="btn-action btn-primary">Xem Chi Tiết</a>
                                            </div>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php require_once __DIR__ . '/layouts/footer.php'; ?>
    </div>

    <script src="assets/js/orders.js"></script>
    <script src="assets/js/headerFooter.js"></script>
    <script src="assets/js/toast.js"></script>
</body>

</html>