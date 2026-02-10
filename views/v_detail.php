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
    <link rel="stylesheet" href="assets/css/detail.css">
    <title><?php echo $pageTitle; ?></title>
</head>

<body>
    <div class="page-wrapper">
        <!-- Header -->
        <?php require_once __DIR__ . '/layouts/header.php'; ?>

        <main class="content">
            <div class="product-detail">
                <!-- ảnh sp -->
                <div class="product-image">
                    <img src="assets/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>

                <!-- thông tin sp -->
                <div class="product-info-detail">
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                    <div class="discount-badge-large">-<?php echo $discount; ?>%</div>
                    <p>
                        <span class="price-old"><?php echo number_format($product['price'], 0, ',', '.'); ?>₫</span>
                        <span class="price-new"><?php echo number_format($newPrice, 0, ',', '.'); ?>₫</span>
                    </p>
                    <div class="product-description">
                        <strong>Thông tin chi tiết:</strong><br>
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-add">Thêm giỏ</button>
                        <button class="btn btn-buy">Mua ngay</button>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <?php require_once __DIR__ . '/layouts/footer.php'; ?>
    </div>

    <script>
        function showToast(message, type = 'success') {
            let toast = document.createElement("div");
            toast.className = `toast ${type}`;
            toast.innerText = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        // Thêm sản phẩm vào giỏ hàng
        function addToCart(productId, quantity = 1, variant = 'Mặc định') {
            fetch('index.php?page=cart&action=add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}&quantity=${quantity}&variant=${encodeURIComponent(variant)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Đã thêm vào giỏ hàng', 'success');
                    } else {
                        showToast(data.message || 'Có lỗi xảy ra', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Có lỗi xảy ra khi thêm vào giỏ hàng', 'error');
                });
        }

        // Xử lý nút "Thêm giỏ"
        document.addEventListener('DOMContentLoaded', function() {
            const btnAdd = document.querySelector('.btn-add');
            if (btnAdd) {
                btnAdd.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = <?= $product['id'] ?>;
                    addToCart(productId);
                });
            }

            // Xử lý nút "Mua ngay"
            const btnBuy = document.querySelector('.btn-buy');
            if (btnBuy) {
                btnBuy.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = <?= $product['id'] ?>;

                    const quantity = 1;
                    window.location.href = `index.php?page=checkout&buy_now_id=${productId}&quantity=${quantity}`;
                });
            }
        });
    </script>
    <script src="assets/js/headerFooter.js"></script>
    <script src="assets/js/toast.js"></script>
</body>

</html>