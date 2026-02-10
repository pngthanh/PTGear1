<?php
// View: Trang đặt hàng thành công
// File: views/v_order_success.php
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/HeaderFooter.css">
    <title>Đặt Hàng Thành Công - PTGear</title>
    <style>
        .success-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 40px;
            text-align: center;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .success-icon {
            font-size: 64px;
            color: #10b981;
            margin-bottom: 20px;
        }
        .success-title {
            font-size: 24px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 10px;
        }
        .success-message {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 30px;
        }
        .order-info {
            background: #f9fafb;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 30px;
        }
        .order-code {
            font-size: 18px;
            font-weight: 600;
            color: #3b82f6;
        }
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        .btn {
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .btn-primary {
            background: #3b82f6;
            color: #ffffff;
        }
        .btn-primary:hover {
            background: #2563eb;
        }
        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
        }
        .btn-secondary:hover {
            background: #e5e7eb;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <!-- Header -->
        <?php require_once __DIR__ . '/layouts/header.php'; ?>

        <!-- Main Content -->
        <div class="content">
            <div class="success-container">
                <i class="fa fa-check-circle success-icon"></i>
                <h1 class="success-title">Đặt Hàng Thành Công!</h1>
                <p class="success-message">Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đang được xử lý.</p>
                
                <?php if (isset($order)): ?>
                    <div class="order-info">
                        <p>Mã đơn hàng:</p>
                        <p class="order-code"><?= htmlspecialchars($order['order_code']) ?></p>
                    </div>
                <?php endif; ?>

                <div class="btn-group">
                    <a href="index.php?page=home" class="btn btn-primary">Tiếp Tục Mua Sắm</a>
                    <a href="index.php?page=orders" class="btn btn-secondary">Xem Đơn Hàng</a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php require_once __DIR__ . '/layouts/footer.php'; ?>
    </div>
</body>

</html>

