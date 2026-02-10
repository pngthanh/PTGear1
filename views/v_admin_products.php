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
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/admin_products.css">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
</head>

<body>
    <div class="page-wrapper">
        <?php require_once __DIR__ . '/layouts/header.php'; ?>

        <div class="admin-container">
            <?php require_once __DIR__ . '/layouts/sidebar_admin.php'; ?>

            <main class="main-content">
                <div class="main-header">
                    <h2><?php echo htmlspecialchars($pageTitle); ?></h2>
                    <div class="main-actions">
                        <form id="filterForm" class="filter-bar" method="GET">
                            <input type="hidden" name="page" value="admin_products">
                            <div class="filter-group">
                                <label for="filterCategory">Danh mục:</label>
                                <select id="filterCategory" name="category" onchange="this.form.submit()">
                                    <option value="0">Tất cả</option>
                                    <?php mysqli_data_seek($categories, 0); ?>
                                    <?php while ($cat = $categories->fetch_assoc()): ?>
                                        <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $filterCategory) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="filterSubcategory">Loại:</label>
                                <select id="filterSubcategory" name="subcategory" onchange="this.form.submit()">
                                    <option value="0">Tất cả</option>
                                    <?php if ($subcategories): ?>
                                        <?php mysqli_data_seek($subcategories, 0); ?>
                                        <?php while ($sub = $subcategories->fetch_assoc()): ?>
                                            <option value="<?= $sub['id'] ?>" <?= ($sub['id'] == $filterSubcategory) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($sub['name']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </form>
                        <button class="btn-manage" onclick="openCategoryModal()">
                            <i class="fa fa-pencil-square-o"></i> Cập nhật Danh mục
                        </button>
                        <button class="btn-add" onclick="openAddModal()">
                            <i class="fa fa-plus"></i> Thêm Sản Phẩm
                        </button>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Ảnh</th>
                                <th>Tên Sản Phẩm</th>
                                <th>Giá</th>
                                <th>Tồn kho</th>
                                <th>Danh mục</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                            <?php $i = 1; ?>
                            <?php while ($product = $products->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td>
                                        <img src="assets/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-table-img">
                                    </td>
                                    <td class="product-name"><?= htmlspecialchars($product['name']) ?></td>
                                    <td><?= number_format($product['price'], 0, ',', '.') ?>đ</td>
                                    <td><?= $product['stock'] ?></td>
                                    <td>
                                        <?= htmlspecialchars($product['category_name']) ?>
                                        <small>(<?= htmlspecialchars($product['subcategory_name']) ?>)</small>
                                    </td>
                                    <td>
                                        <button class="btn-edit"
                                            data-id="<?= $product['id'] ?>"
                                            data-name="<?= htmlspecialchars($product['name']) ?>"
                                            data-description="<?= htmlspecialchars($product['description']) ?>"
                                            data-price="<?= $product['price'] ?>"
                                            data-stock="<?= $product['stock'] ?>"
                                            data-category_id="<?= $product['category_id'] ?>"
                                            data-subcategory_id="<?= $product['subcategory_id'] ?>"
                                            data-image="assets/<?= htmlspecialchars($product['image']) ?>">Sửa</button>
                                        <button class="btn-delete" data-id="<?= $product['id'] ?>">Xóa</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>

        <div id="categoryModal" class="modal hidden">
            <div class="modal-content">
                <h3>Cập nhật Danh mục</h3>
                <form id="categoryForm">
                    <div class="form-group">
                        <label for="catModal_Category">Chọn Danh Mục Chính</label>
                        <select id="catModal_Category" name="cat_id" required>
                            <option value="">Chọn</option>
                            <?php mysqli_data_seek($categories, 0); ?>
                            <?php while ($cat = $categories->fetch_assoc()): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="catModal_Subcategory">Chọn Danh Mục Con</label>
                        <select id="catModal_Subcategory" name="sub_id" required>
                            <option value="">Chọn danh mục chính trước</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="catModal_Name">Tên (Mới)</label>
                        <input type="text" id="catModal_Name" name="name" required placeholder="Nhập tên mới hoặc để sửa">
                    </div>
                    <div class="modal-buttons">
                        <button type="submit">Lưu</button>
                        <button type="button" onclick="closeModal('categoryModal')">Hủy</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="addModal" class="modal hidden">
            <div class="modal-content large">
                <h3>Thêm Sản Phẩm Mới</h3>
                <form id="addForm" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="add_name">Tên sản phẩm</label>
                        <input type="text" id="add_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="add_description">Mô tả</label>
                        <textarea id="add_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="add_price">Giá</label>
                            <input type="number" id="add_price" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="add_stock">Tồn kho</label>
                            <input type="number" id="add_stock" name="stock" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="add_category_id">Danh mục chính</label>
                            <select id="add_category_id" name="category_id" required>
                                <option value="">Chọn</option>
                                <?php mysqli_data_seek($categories, 0); ?>
                                <?php while ($cat = $categories->fetch_assoc()): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="add_subcategory_id">Danh mục con</label>
                            <select id="add_subcategory_id" name="subcategory_id" required>
                                <option value="">Chọn danh mục chính trước</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="add_image">Ảnh sản phẩm (Chọn file)</label>
                        <input type="file" id="add_image" name="image" accept="image/png, image/jpeg, image/webp" required>
                    </div>
                    <div class="modal-buttons">
                        <button type="submit">Lưu</button>
                        <button type="button" onclick="closeModal('addModal')">Hủy</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal hidden">
        <div class="modal-content large">
            <h3>Sửa Sản Phẩm</h3>
            <form id="editForm" enctype="multipart/form-data">
                <input type="hidden" id="edit_id" name="id">
                <div class="form-group">
                    <label for="edit_name">Tên sản phẩm</label>
                    <input type="text" id="edit_name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="edit_description">Mô tả</label>
                    <textarea id="edit_description" name="description" rows="3"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_price">Giá</label>
                        <input type="number" id="edit_price" name="price" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_stock">Tồn kho</label>
                        <input type="number" id="edit_stock" name="stock" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_category_id">Danh mục chính</label>
                        <select id="edit_category_id" name="category_id" required>
                            <option value="">Chọn</option>
                            <?php mysqli_data_seek($categories, 0); ?>
                            <?php while ($cat = $categories->fetch_assoc()): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_subcategory_id">Danh mục con</label>
                        <select id="edit_subcategory_id" name="subcategory_id" required>
                            <option value="">Chọn danh mục chính trước</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="edit_image">Ảnh sản phẩm</label>
                    <input type="file" id="edit_image" name="image" accept="image/png, image/jpeg, image/webp">
                    <img id="editImagePreview" src="" alt="Ảnh hiện tại" style="max-width: 100px; margin-top: 10px;">
                </div>
                <div class="modal-buttons">
                    <button type="submit">Lưu thay đổi</button>
                    <button type="button" onclick="closeModal('editModal')">Hủy</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="modal hidden">
        <div class="modal-content">
            <h3>Xác Nhận Xóa</h3>
            <p>Bạn có chắc chắn muốn xóa sản phẩm này?</p>
            <form id="deleteForm">
                <input type="hidden" id="deleteId" name="id">
                <div class="modal-buttons">
                    <button type="submit" class="btn-danger">Xóa</button>
                    <button type="button" onclick="closeModal('deleteModal')">Hủy</button>
                </div>
            </form>
        </div>
    </div>

    <?php require_once __DIR__ . '/layouts/footer.php'; ?>
    </div>

    <div id="toast" class="toast hidden"></div>

    <script src="assets/js/admin_products.js"></script>
    <script src="assets/js/headerFooter.js"></script>
    <script src="assets/js/toast.js"></script>
</body>

</html>