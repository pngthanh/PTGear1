<?php

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/HeaderFooter.css">
    <link rel="stylesheet" href="assets/css/cart.css">
    <title>Giỏ Hàng - PTGear</title>
</head>

<body>
    <div class="page-wrapper">
        <!-- Header -->
        <?php require_once __DIR__ . '/layouts/header.php'; ?>

        <!-- Giỏ Hàng -->
        <div class="content">
            <div class="cart-container">
                <div class="cart-layout">
                    <!-- Giỏ Hàng Của Bạn -->
                    <div class="cart-left">
                        <div class="cart-header">
                            <h1 class="cart-title">Giỏ Hàng Của Bạn</h1>
                            <a href="index.php?page=home" class="btn-back">
                                <i class="fa fa-arrow-left"></i> Trở về
                            </a>
                        </div>

                        <!-- Cart Table -->
                        <div class="cart-table-wrapper">
                            <table class="cart-table">
                                <thead>
                                    <tr>
                                        <th class="col-checkbox">
                                            <input type="checkbox" id="selectAll" class="checkbox-input">
                                        </th>
                                        <th class="col-product">Sản phẩm</th>
                                        <th class="col-price">Đơn giá</th>
                                        <th class="col-quantity">Số lượng</th>
                                        <th class="col-subtotal">Thành tiền</th>
                                        <th class="col-action">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody id="cartItems">
                                    <?php if (empty($cartItems)): ?>
                                        <tr>
                                            <td colspan="6" class="empty-cart">
                                                <p>Giỏ hàng của bạn đang trống</p>
                                                <a href="index.php?page=products&category=1" class="btn-shopping">Tiếp tục mua sắm</a>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($cartItems as $item): ?>
                                            <tr class="cart-item" data-product-id="<?= $item['product_id'] ?>">
                                                <td class="col-checkbox">
                                                    <input type="checkbox" class="checkbox-input item-checkbox" checked>
                                                </td>
                                                <td class="col-product">
                                                    <div class="product-info">
                                                        <img src="assets/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-image">
                                                        <div class="product-details">
                                                            <h3 class="product-name"><?= htmlspecialchars($item['name']) ?></h3>
                                                            <p class="product-variant">Phân loại: <?= htmlspecialchars($item['variant'] ?? 'Mặc định') ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="col-price">
                                                    <div class="price-info">
                                                        <?php if ($item['original_price'] > $item['price']): ?>
                                                            <span class="original-price"><?= number_format($item['original_price'], 0, ',', '.') ?>₫</span>
                                                        <?php endif; ?>
                                                        <span class="current-price"><?= number_format($item['price'], 0, ',', '.') ?>₫</span>
                                                    </div>
                                                </td>
                                                <td class="col-quantity">
                                                    <div class="quantity-control">
                                                        <button type="button" class="btn-quantity btn-minus" onclick="updateQuantity(<?= $item['product_id'] ?>, -1)">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                        <input type="number" class="quantity-input" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?? 999 ?>" onchange="updateQuantityInput(<?= $item['product_id'] ?>, this.value)">
                                                        <button type="button" class="btn-quantity btn-plus" onclick="updateQuantity(<?= $item['product_id'] ?>, 1)">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="col-subtotal">
                                                    <span class="subtotal-price"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>₫</span>
                                                </td>
                                                <td class="col-action">
                                                    <button type="button" class="btn-delete" onclick="removeItem(<?= $item['product_id'] ?>)">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tóm Tắt Đơn Hàng -->
                    <div class="cart-right">
                        <div class="order-summary">
                            <h2 class="summary-title">Tóm Tắt Đơn Hàng</h2>

                            <div class="summary-content">
                                <div class="summary-row">
                                    <span class="summary-label">Tạm tính (<span id="selectedCount">0</span> sản phẩm)</span>
                                    <span class="summary-value" id="subtotal">0₫</span>
                                </div>

                                <div class="summary-row">
                                    <span class="summary-label">Giảm giá</span>
                                    <span class="summary-value discount" id="discount">-0₫</span>
                                </div>

                                <div class="voucher-section">
                                    <input type="text" id="voucherCode" class="voucher-input" placeholder="Nhập mã giảm giá">
                                    <button type="button" class="btn-apply" onclick="applyVoucher()">Áp Dụng</button>
                                </div>

                                <div class="summary-divider"></div>

                                <div class="summary-row total-row">
                                    <span class="summary-label">Tổng cộng</span>
                                    <span class="summary-value total-price" id="total">0₫</span>
                                </div>

                                <button type="button" class="btn-checkout" onclick="proceedCheckout()">
                                    Tiến Hành Thanh Toán
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

    <script src="assets/js/cart.js"></script>
    <script src="assets/js/headerFooter.js"></script>
    <script src="assets/js/toast.js"></script>
</body>

</html>