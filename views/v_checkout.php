<?php
// View: Trang thanh toán
// File: views/v_checkout.php
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/HeaderFooter.css">
    <link rel="stylesheet" href="assets/css/checkout.css">
    <title>Thanh Toán - PTGear</title>
</head>

<body>
    <div class="page-wrapper">
        <!-- Header -->
        <?php require_once __DIR__ . '/layouts/header.php'; ?>

        <!-- hanh Toán -->
        <div class="content">
            <div class="checkout-container">
                <div class="checkout-layout">
                    <!-- Form thông tin người nhận -->
                    <div class="checkout-left">
                        <div class="checkout-header">
                            <h1 class="checkout-title">Thanh Toán</h1>
                        </div>

                        <!-- Form thông tin người nhận -->
                        <form id="checkoutForm" method="POST" action="index.php?page=checkout" class="checkout-form">
                            <?php if (isset($_GET['buy_now_id'])):
                            ?>
                                <input type="hidden" name="buy_now_id" value="<?= htmlspecialchars($_GET['buy_now_id']) ?>">
                                <input type="hidden" name="buy_now_quantity" value="<?= htmlspecialchars($_GET['quantity'] ?? 1) ?>">
                            <?php elseif (isset($_GET['selected_ids'])):
                            ?>
                                <input type="hidden" name="selected_ids" value="<?= htmlspecialchars($_GET['selected_ids']) ?>">
                            <?php endif; ?>
                            <div class="form-section">
                                <h2 class="section-title">Thông tin người nhận</h2>

                                <div class="form-group">
                                    <label for="recipient_name">Tên người nhận <span class="required">*</span></label>
                                    <input
                                        type="text"
                                        id="recipient_name"
                                        name="recipient_name"
                                        class="form-input"
                                        value="<?= htmlspecialchars($user['fullname'] ?? $user['username'] ?? '') ?>"
                                        required
                                        placeholder="Nhập tên người nhận">
                                </div>

                                <div class="form-group">
                                    <label for="phone">Số điện thoại <span class="required">*</span></label>
                                    <input
                                        type="text"
                                        id="phone"
                                        name="phone"
                                        class="form-input"
                                        value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                                        required
                                        placeholder="Nhập số điện thoại"
                                        pattern="[0-9]{10,11}">
                                </div>

                                <div class="form-group">
                                    <label for="shipping_address">Địa chỉ giao hàng <span class="required">*</span></label>
                                    <textarea
                                        id="shipping_address"
                                        name="shipping_address"
                                        class="form-input form-textarea"
                                        required
                                        rows="4"
                                        placeholder="Nhập địa chỉ giao hàng chi tiết"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                                </div>
                            </div>

                            <!-- Phương thức thanh toán -->
                            <div class="form-section">
                                <h2 class="section-title">Phương thức thanh toán</h2>

                                <div class="payment-method">
                                    <div class="payment-option">
                                        <input type="radio" id="cod" name="payment_method" value="cod" checked>
                                        <label for="cod" class="payment-label">
                                            <i class="fa fa-money"></i>
                                            <div class="payment-info">
                                                <span class="payment-name">Thanh toán khi nhận hàng (COD)</span>
                                                <span class="payment-desc">Thanh toán bằng tiền mặt khi nhận hàng</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông báo lỗi/thành công -->
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-error">
                                    <i class="fa fa-exclamation-circle"></i>
                                    <?= htmlspecialchars($error) ?>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>

                    <!-- Tóm tắt đơn hàng -->
                    <div class="checkout-right">
                        <div class="order-summary">
                            <div class="summary-header">
                                <h2 class="summary-title">Tóm Tắt Đơn Hàng</h2>
                                <a href="index.php?page=cart" class="btn-back-summary">
                                    <i class="fa fa-arrow-left"></i> Trở về
                                </a>
                            </div>

                            <div class="summary-content">
                                <!-- Danh sách sản phẩm -->
                                <div class="summary-products">
                                    <?php if (empty($cartItems)): ?>
                                        <p class="empty-message">Giỏ hàng trống</p>
                                    <?php else: ?>
                                        <?php foreach ($cartItems as $item): ?>
                                            <div class="summary-product-item">
                                                <img src="assets/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-thumb">
                                                <div class="product-summary-info">
                                                    <h4 class="product-summary-name"><?= htmlspecialchars($item['name']) ?></h4>
                                                    <p class="product-summary-variant">Phân loại: <?= htmlspecialchars($item['variant'] ?? 'Mặc định') ?></p>
                                                    <div class="product-summary-quantity">
                                                        <span>Số lượng: <?= $item['quantity'] ?></span>
                                                        <span class="product-summary-price"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>₫</span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>

                                <div class="summary-divider"></div>

                                <!-- Tổng tiền -->
                                <div class="summary-row">
                                    <span class="summary-label">Tạm tính</span>
                                    <span class="summary-value" id="subtotal"><?= number_format($subtotal, 0, ',', '.') ?>₫</span>
                                </div>

                                <div class="summary-row">
                                    <span class="summary-label">Phí vận chuyển</span>
                                    <span class="summary-value">30.000₫</span>
                                </div>

                                <div class="summary-row">
                                    <span class="summary-label">Giảm giá</span>
                                    <span class="summary-value discount">-0₫</span>
                                </div>

                                <div class="summary-divider"></div>

                                <div class="summary-row total-row">
                                    <span class="summary-label">Tổng cộng</span>
                                    <span class="summary-value total-price" id="total"><?= number_format($total, 0, ',', '.') ?>₫</span>
                                </div>

                                <!-- Nút Đặt Hàng -->
                                <button type="submit" form="checkoutForm" class="btn-submit">
                                    <i class="fa fa-shopping-cart"></i>
                                    Đặt Hàng
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php require_once __DIR__ . '/layouts/footer.php'; ?>
    </div>

    <script src="assets/js/checkout.js"></script>
    <script src="assets/js/headerFooter.js"></script>
    <script src="assets/js/toast.js"></script>
</body>

</html>