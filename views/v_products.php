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
    <link rel="stylesheet" href="assets/css/products.css">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
</head>

<body>
    <div class="page-wrapper">
        <!-- Header -->
        <?php require_once __DIR__ . '/layouts/header.php'; ?>

        <main class="content">
            <div class="product-page">

                <form method="get" action="index.php" class="filter-bar">

                    <input type="hidden" name="page" value="products">
                    <input type="hidden" name="category" value="<?php echo $categoryId; ?>">

                    <!-- Cây so sánh  -->
                    <div class="compare-inline">
                        <strong>So sánh:</strong>
                        <div class="compare-slot-inline" id="slot1">Sản phẩm 1</div>
                        <div class="compare-slot-inline" id="slot2">Sản phẩm 2</div>
                        <button type="button" class="btn btn-compare" onclick="openCompare()">So sánh</button>
                        <button type="button" class="btn btn-clear" onclick="clearCompare()">Xóa</button>
                    </div>

                    <!-- Filter  -->
                    <div class="filter-options">
                        <label>Sắp xếp:</label>
                        <select name="sort" onchange="this.form.submit()">
                            <option value="">Mới nhất</option>
                            <option value="price_asc" <?php echo ($currentSort == 'price_asc') ? 'selected' : ''; ?>>Giá tăng dần</option>
                            <option value="price_desc" <?php echo ($currentSort == 'price_desc') ? 'selected' : ''; ?>>Giá giảm dần</option>
                            <option value="name" <?php echo ($currentSort == 'name') ? 'selected' : ''; ?>>Theo tên</option>
                        </select>

                        <label>Loại:</label>
                        <select name="subcategory" onchange="this.form.submit()">
                            <option value="0">Tất cả</option>
                            <?php
                            mysqli_data_seek($subcategories, 0);
                            while ($sub = $subcategories->fetch_assoc()):
                            ?>
                                <option value="<?php echo $sub['id']; ?>" <?php echo ($filterSub == $sub['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($sub['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </form>

                <!-- ds sp -->
                <div class="product-grid">
                    <?php while ($row = $products->fetch_assoc()):
                        $discount = $row['discount_percent'];
                        $newPrice = $row['price'];
                        if ($discount > 0) {
                            $newPrice = $row['price'] * (100 - $discount) / 100;
                        }
                    ?>
                        <div class="product-card"
                            data-id="<?php echo $row['id']; ?>"
                            data-name="<?php echo htmlspecialchars($row['name']); ?>"
                            data-price="<?php echo $row['price']; ?>"
                            data-newprice="<?php echo $newPrice; ?>"
                            data-image="assets/<?php echo htmlspecialchars($row['image']); ?>"
                            data-description="<?php echo htmlspecialchars($row['description']); ?>">

                            <div class="discount-badge">-<?php echo $discount; ?>%</div>
                            <i class="fa fa-link compare-icon" aria-hidden="true" onclick="addToCompare(this)"></i>

                            <a href="index.php?page=detail&id=<?php echo $row['id']; ?>">
                                <img src="assets/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            </a>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                                <p>
                                    <span class="price-old"><?php echo number_format($row['price'], 0, ',', '.'); ?>₫</span>
                                    <span class="price-new"><?php echo number_format($newPrice, 0, ',', '.'); ?>₫</span>
                                </p>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-add">Thêm giỏ</button>
                                <button class="btn btn-buy">Mua ngay</button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal so sánh -->
    <div id="compareModal" class="modal">
        <div class="modal-content">
            <h2>So sánh sản phẩm</h2>
            <table>
                <thead>
                    <tr>
                        <th>Thông tin</th>
                        <th>Sản phẩm 1</th>
                        <th>Sản phẩm 2</th>
                    </tr>
                </thead>
                <tbody id="compareTable"></tbody>
            </table>
            <div class="modal-footer">
                <button class="btn btn-close" onclick="closeCompareModal()">Đóng</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php require_once __DIR__ . '/layouts/footer.php'; ?>

    <script src="assets/js/headerFooter.js"></script>
    <script src="assets/js/products.js"></script>
    <script src="assets/js/toast.js"></script>
</body>

</html>