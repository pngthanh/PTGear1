<?php
$status_map = [
    'pending' => 'Chờ Xử Lý',
    'confirmed' => 'Đã Xác Nhận',
    'shipping' => 'Đang Giao',
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
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/admin_orders.css">
    <title>Quản Lý Đơn Hàng - Admin</title>
</head>

<body>
    <div class="page-wrapper">
        <?php require_once __DIR__ . '/layouts/header.php'; ?>

        <div class="admin-container">
            <?php require_once __DIR__ . '/layouts/sidebar_admin.php'; ?>

            <div class="main-content">
                <h1 class="admin-title">Quản Lý Đơn Hàng</h1>

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Mã ĐH</th>
                            <th>Khách Hàng</th>
                            <th>SĐT</th>
                            <th>Tổng Tiền</th>
                            <th>Trạng Thái</th>
                            <th>Ngày Đặt</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody id="order-table-body">
                        <?php if ($orders->num_rows > 0): ?>
                            <?php while ($order = $orders->fetch_assoc()): ?>
                                <?php
                                $status = $order['status'];
                                $status_text = $status_map[$status] ?? ucfirst($status);
                                ?>
                                <tr data-order-id="<?= $order['id'] ?>">
                                    <td>#<?= htmlspecialchars($order['order_code']) ?></td>
                                    <td><?= htmlspecialchars($order['recipient_name']) ?></td>
                                    <td><?= htmlspecialchars($order['phone']) ?></td>
                                    <td><?= number_format($order['total_price'], 0, ',', '.') ?>đ</td>
                                    <td>
                                        <span class="status-badge status-<?= htmlspecialchars($status) ?>">
                                            <?= htmlspecialchars($status_text) ?>
                                        </span>
                                    </td>
                                    <td><?= date("d/m/Y", strtotime($order['created_at'])) ?></td>
                                    <td class="action-buttons">
                                        <a href="index.php?page=admin_order_detail&id=<?= $order['id'] ?>" class="btn-action btn-view">Xem</a>

                                        <?php if ($status === 'pending'): ?>
                                            <button class="btn-action btn-approve" data-order-id="<?= $order['id'] ?>">Duyệt</button>
                                            <button class="btn-action btn-cancel-admin" data-order-id="<?= $order['id'] ?>">Hủy</button>
                                        <?php else: ?>
                                            <button class="btn-action" disabled>Duyệt</button>
                                            <button class="btn-action" disabled>Hủy</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">Không có đơn hàng nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php require_once __DIR__ . '/layouts/footer.php'; ?>

        <div id="toast" class="toast hidden"></div>
    </div>

    <script src="assets/js/admin_orders.js"></script>
    <script src="assets/js/headerFooter.js"></script>
    <script src="assets/js/toast.js"></script>
</body>

</html>