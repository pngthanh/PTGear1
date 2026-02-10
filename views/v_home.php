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
    <link rel="stylesheet" href="assets/css/home.css">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
</head>

<body>
    <div class="page-wrapper">
        <?php require_once __DIR__ . '/layouts/header.php'; ?>

        <main class="main">
            <div class="slideshow">
                <img src="assets/img/slide_1.png" class="slide active" alt="Banner 1">
                <img src="assets/img/slide_2.jpg" class="slide" alt="Banner 2">
            </div>

            <section class="flash-sale">
                <div class="flash-container">
                    <div class="flash-header">
                        <div class="flash-title">
                            <img src="assets/img/fs.png" alt="Flash Sale" class="flash-sale-img">
                            <div class="timer">
                                <div class="time-box">
                                    <span id="days">00</span>
                                    <small>Ngày</small>
                                </div>
                                <div class="time-box">
                                    <span id="hours">00</span>
                                    <small>Giờ</small>
                                </div>
                                <div class="time-box">
                                    <span id="minutes">00</span>
                                    <small>Phút</small>
                                </div>
                                <div class="time-box">
                                    <span id="seconds">00</span>
                                    <small>Giây</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="flash-divider">
                    <div class="flash-grid">
                        <?php
                        mysqli_data_seek($flashSaleProducts, 0);
                        while ($product = $flashSaleProducts->fetch_assoc()): ?>
                            <?php
                            $discount = $product['discount_percent'];
                            $newPrice = $product['price'];
                            if ($discount > 0) {
                                $newPrice = $product['price'] * (100 - $discount) / 100;
                            }
                            $shortName = mb_strlen($product['name']) > 50 ? mb_substr($product['name'], 0, 50) . '...' : $product['name'];
                            ?>

                            <div class="flash-item" data-id="<?php echo $product['id']; ?>">
                                <?php if ($discount > 0): ?>
                                    <div class="discount-tag">-<?php echo $discount; ?>%</div>
                                <?php endif; ?>

                                <a href="index.php?page=detail&id=<?php echo $product['id']; ?>">
                                    <img src="assets/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                </a>

                                <h3>
                                    <a href="index.php?page=detail&id=<?php echo $product['id']; ?>" class="product-name-link">
                                        <?php echo htmlspecialchars(substr($shortName, 0, 20)); ?>...
                                    </a>
                                </h3>
                                <p class="desc"><?php echo htmlspecialchars(substr($product['description'], 0, 30)); ?>...</p>

                                <?php if ($discount > 0): ?>
                                    <p class="original-price"><s><?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</s></p>
                                    <p class="discounted-price"><?php echo number_format($newPrice, 0, ',', '.'); ?> VNĐ</p>
                                <?php else: ?>
                                    <p class="original-price" style="visibility: hidden;"><s>&nbsp;</s></p>
                                    <p class="discounted-price"><?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</p>
                                <?php endif; ?>

                                <p class="stock"><?php echo $product['stock'] > 0 ? 'Còn ' . $product['stock'] . ' sản phẩm' : '<span class="out-of-stock">Hết Hàng</span>'; ?></p>
                                <div class="button-group">
                                    <button class="btn-add">Thêm vào giỏ</button>
                                    <button class="btn-buy" data-id="<?php echo $product['id']; ?>">Mua ngay</button>
                                </div>
                            </div>
                        <?php endwhile; ?>

                        <button class="flash-btn prev">
                            <i class="fa fa-angle-left" aria-hidden="true"></i>
                        </button>

                        <button class="flash-btn next">
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </button>
                    </div>
            </section>

            <section class="offers">
                <div class="offer-container">
                    <h2 class="offer-title">Ưu đãi dành cho bạn</h2>
                    <div class="offer-tabs">
                        <button class="tab-button active" onclick="showProduct('new')">
                            Sản phẩm mới
                        </button>
                        <button class="tab-button" onclick="showProduct('best-seller')">
                            Sản phẩm bán chạy
                        </button>
                        <button class="tab-button" onclick="showProduct('component')">
                            Linh kiện nổi bật
                        </button>
                        <button class="tab-button" onclick="showProduct('accessory')">
                            Phụ kiện nổi bật
                        </button>
                    </div>
                    <div class="offer-content">

                        <div class="product-item active" id="new">
                            <?php
                            mysqli_data_seek($newProducts, 0);
                            while ($product = $newProducts->fetch_assoc()):
                                $discount = $product['discount_percent'];
                                $newPrice = $product['price'];
                                if ($discount > 0) {
                                    $newPrice = $product['price'] * (100 - $discount) / 100;
                                }
                                $shortName = mb_strlen($product['name']) > 50 ? mb_substr($product['name'], 0, 50) . '...' : $product['name'];
                            ?>
                                <div class="sanpham" data-id="<?php echo $product['id']; ?>">
                                    <a href="index.php?page=detail&id=<?php echo $product['id']; ?>">
                                        <img src="assets/<?php echo htmlspecialchars($product['image']); ?>"
                                            alt="<?php echo htmlspecialchars($product['name']); ?>" />
                                    </a>
                                    <div class="product-info">
                                        <h3>
                                            <a href="index.php?page=detail&id=<?php echo $product['id']; ?>" class="product-name-link">
                                                <?php echo htmlspecialchars($shortName); ?>
                                            </a>
                                        </h3>
                                        <p class="price">
                                            <?php if ($discount > 0): ?>
                                                <span style="text-decoration: line-through; color: #888; font-size: 0.9em;">
                                                    <?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ
                                                </span>
                                                <br>
                                                <span style="color: red; font-weight: bold;">
                                                    <?php echo number_format($newPrice, 0, ',', '.'); ?> VNĐ
                                                </span>
                                            <?php else: ?>
                                                <span style="color: red; font-weight: bold;">
                                                    <?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ
                                                </span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="button-group">
                                        <button class="btn-add">Thêm vào giỏ</button>
                                        <button class="btn-buy" data-id="<?php echo $product['id']; ?>">Mua ngay</button>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>

                        <div class="product-item" id="best-seller">
                            <?php
                            mysqli_data_seek($bestSellerProducts, 0);
                            while ($product = $bestSellerProducts->fetch_assoc()):
                                $discount = $product['discount_percent'];
                                $newPrice = $product['price'];
                                if ($discount > 0) {
                                    $newPrice = $product['price'] * (100 - $discount) / 100;
                                }
                                $shortName = mb_strlen($product['name']) > 50 ? mb_substr($product['name'], 0, 50) . '...' : $product['name'];
                            ?>
                                <div class="sanpham" data-id="<?php echo $product['id']; ?>">
                                    <a href="index.php?page=detail&id=<?php echo $product['id']; ?>">
                                        <img src="assets/<?php echo htmlspecialchars($product['image']); ?>"
                                            alt="<?php echo htmlspecialchars($product['name']); ?>" />
                                    </a>
                                    <div class="product-info">
                                        <h3>
                                            <a href="index.php?page=detail&id=<?php echo $product['id']; ?>" class="product-name-link">
                                                <?php echo htmlspecialchars($shortName); ?>
                                            </a>
                                        </h3>
                                        <p class="price">
                                            <?php if ($discount > 0): ?>
                                                <span style="text-decoration: line-through; color: #888; font-size: 0.9em;">
                                                    <?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ
                                                </span><br>
                                                <span style="color: red; font-weight: bold;">
                                                    <?php echo number_format($newPrice, 0, ',', '.'); ?> VNĐ
                                                </span>
                                            <?php else: ?>
                                                <span style="color: red; font-weight: bold;">
                                                    <?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ
                                                </span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="button-group">
                                        <button class="btn-add">Thêm vào giỏ</button>
                                        <button class="btn-buy" data-id="<?php echo $product['id']; ?>">Mua ngay</button>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>

                        <div class="product-item" id="component">
                            <?php
                            mysqli_data_seek($featuredComponents, 0);
                            while ($product = $featuredComponents->fetch_assoc()):
                                $discount = $product['discount_percent'];
                                $newPrice = $product['price'];
                                if ($discount > 0) {
                                    $newPrice = $product['price'] * (100 - $discount) / 100;
                                }
                                $shortName = mb_strlen($product['name']) > 50 ? mb_substr($product['name'], 0, 50) . '...' : $product['name'];
                            ?>
                                <div class="sanpham" data-id="<?php echo $product['id']; ?>">
                                    <a href="index.php?page=detail&id=<?php echo $product['id']; ?>">
                                        <img src="assets/<?php echo htmlspecialchars($product['image']); ?>"
                                            alt="<?php echo htmlspecialchars($product['name']); ?>" />
                                    </a>
                                    <div class="product-info">
                                        <h3>
                                            <a href="index.php?page=detail&id=<?php echo $product['id']; ?>" class="product-name-link">
                                                <?php echo htmlspecialchars($shortName); ?>
                                            </a>
                                        </h3>
                                        <p class="price">
                                            <?php if ($discount > 0): ?>
                                                <span style="text-decoration: line-through; color: #888; font-size: 0.9em;">
                                                    <?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ
                                                </span><br>
                                                <span style="color: red; font-weight: bold;">
                                                    <?php echo number_format($newPrice, 0, ',', '.'); ?> VNĐ
                                                </span>
                                            <?php else: ?>
                                                <span style="color: red; font-weight: bold;">
                                                    <?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ
                                                </span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="button-group">
                                        <button class="btn-add">Thêm vào giỏ</button>
                                        <button class="btn-buy" data-id="<?php echo $product['id']; ?>">Mua ngay</button>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>

                        <div class="product-item" id="accessory">
                            <?php
                            mysqli_data_seek($featuredAccessories, 0);
                            while ($product = $featuredAccessories->fetch_assoc()):
                                $discount = $product['discount_percent'];
                                $newPrice = $product['price'];
                                if ($discount > 0) {
                                    $newPrice = $product['price'] * (100 - $discount) / 100;
                                }
                                $shortName = mb_strlen($product['name']) > 50 ? mb_substr($product['name'], 0, 50) . '...' : $product['name'];
                            ?>
                                <div class="sanpham" data-id="<?php echo $product['id']; ?>">
                                    <a href="index.php?page=detail&id=<?php echo $product['id']; ?>">
                                        <img src="assets/<?php echo htmlspecialchars($product['image']); ?>"
                                            alt="<?php echo htmlspecialchars($product['name']); ?>" />
                                    </a>
                                    <div class="product-info">
                                        <h3>
                                            <a href="index.php?page=detail&id=<?php echo $product['id']; ?>" class="product-name-link">
                                                <?php echo htmlspecialchars($shortName); ?>
                                            </a>
                                        </h3>
                                        <p class="price">
                                            <?php if ($discount > 0): ?>
                                                <span style="text-decoration: line-through; color: #888; font-size: 0.9em;">
                                                    <?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ
                                                </span><br>
                                                <span style="color: red; font-weight: bold;">
                                                    <?php echo number_format($newPrice, 0, ',', '.'); ?> VNĐ
                                                </span>
                                            <?php else: ?>
                                                <span style="color: red; font-weight: bold;">
                                                    <?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ
                                                </span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="button-group">
                                        <button class="btn-add">Thêm vào giỏ</button>
                                        <button class="btn-buy" data-id="<?php echo $product['id']; ?>">Mua ngay</button>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <?php require_once __DIR__ . '/layouts/footer.php'; ?>
    </div>


    <script src="assets/js/products.js"></script>
    <script src="assets/js/home.js"></script>
    <script src="assets/js/headerFooter.js"></script>
    <script src="assets/js/toast.js"></script>
</body>

</html>