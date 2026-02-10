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
    <link rel="stylesheet" href="assets/css/admin_dashboard.css">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
</head>

<body>
    <div class="page-wrapper">
        <?php require_once __DIR__ . '/layouts/header.php'; ?>

        <div class="admin-container">
            <?php require_once __DIR__ . '/layouts/sidebar_admin.php'; ?>

            <main class="main-content">
                <h2><?php echo htmlspecialchars($pageTitle); ?></h2>

                <div class="stat-cards">
                    <div class="stat-card blue">
                        <i class="fa fa-money"></i>
                        <div class="stat-info">
                            <h4>Doanh Thu Hôm Nay</h4>
                            <p><?= number_format($stats['todayRevenue'] ?? 0, 0, ',', '.') ?>đ</p>
                        </div>
                    </div>
                    <div class="stat-card green">
                        <i class="fa fa-shopping-cart"></i>
                        <div class="stat-info">
                            <h4>Đơn Hàng Hôm Nay</h4>
                            <p><?= $stats['todayOrders'] ?? 0 ?></p>
                        </div>
                    </div>
                    <div class="stat-card yellow">
                        <i class="fa fa-user-plus"></i>
                        <div class="stat-info">
                            <h4>Khách Hàng Mới</h4>
                            <p><?= $stats['newCustomers'] ?? 0 ?></p>
                        </div>
                    </div>
                    <div class="stat-card red">
                        <i class="fa fa-archive"></i>
                        <div class="stat-info">
                            <h4>Sản Phẩm Tồn Kho</h4>
                            <p><?= $stats['totalStock'] ?? 0 ?></p>
                        </div>
                    </div>
                </div>

                <div class="chart-container">
                    <div class="chart-card">
                        <h3>Doanh thu 7 ngày qua</h3>
                        <div class="chart-wrapper-line">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-card small">
                        <h3>Tỷ trọng danh mục (đã bán)</h3>
                        <div class="chart-wrapper-doughnut">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-card">
                        <h3>Đơn Hàng Mới Nhất</h3>
                        <table class="recent-orders-table">
                            <thead>
                                <tr>
                                    <th>Mã ĐH</th>
                                    <th>Khách Hàng</th>
                                    <th>Tổng Tiền</th>
                                    <th>Trạng Thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($order = $recentOrders->fetch_assoc()): ?>
                                    <tr>
                                        <td>#<?= $order['id'] ?></td>
                                        <td><?= htmlspecialchars($order['fullname']) ?></td>
                                        <td><?= number_format($order['total_price'], 0, ',', '.') ?>đ</td>
                                        <td>
                                            <span class="status <?= $order['status'] ?>">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-card small">
                        <h3>Sản Phẩm Bán Chạy</h3>
                        <ul class="bestseller-list">
                            <?php while ($product = $bestSellers->fetch_assoc()): ?>
                                <li>
                                    <span class="product-name"><?= htmlspecialchars($product['product_name']) ?></span>
                                    <span class="product-sold"><?= $product['total_sold'] ?></span>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>

            </main>
        </div>

        <?php require_once __DIR__ . '/layouts/footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/admin_dashboard.js"></script>
    <script src="assets/js/toast.js"></script>

    <script>
        // Truyền dữ liệu từ PHP sang JavaScript
        const revenueLabels = <?php echo json_encode($chartLabels7Days); ?>;
        const revenueData = <?php echo json_encode($chartData7Days); ?>;

        const categoryLabels = <?php echo json_encode($chartLabelsCategory); ?>;
        const categoryData = <?php echo json_encode($chartDataCategory); ?>;
        const categoryColors = <?php echo json_encode($chartColorsCategory); ?>;

        // Khởi tạo biểu đồ
        initRevenueChart(revenueLabels, revenueData);
        initCategoryChart(categoryLabels, categoryData, categoryColors);
    </script>
</body>

</html>