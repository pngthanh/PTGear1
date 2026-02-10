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
    <link rel="stylesheet" href="assets/css/user.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
</head>

<body>
    <div class="page-wrapper">
        <!-- Header -->
        <?php require_once __DIR__ . '/layouts/header.php'; ?>

        <!-- Layout Admin -->
        <div class="admin-container">
            <?php require_once __DIR__ . '/layouts/sidebar_admin.php'; ?>

            <main class="main-content">
                <div class="main-header">
                    <h2><?php echo htmlspecialchars($pageTitle); ?></h2>
                    <div class="main-actions">
                        <div class="search-bar">
                            <input type="text" id="searchUser" placeholder="Tìm kiếm tài khoản...">
                            <i class="fa fa-search"></i>
                        </div>
                        <button class="btn-add" onclick="openAddModal()">
                            <i class="fa fa-plus"></i> Thêm Tài Khoản
                        </button>
                    </div>
                </div>

                <!-- Bảng User -->
                <div class="table-wrapper">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tài Khoản</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Vai Trò</th>
                                <th>Trạng Thái</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            <?php
                            while ($row = $users->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo $row['stt']; ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
                                    <td><?php echo $row['role']; ?></td>
                                    <td>
                                        <span class="status <?php echo $row['status']; ?>">
                                            <?php echo $row['status'] === 'active' ? 'Hoạt động' : 'Bị khóa'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn-edit"
                                            data-id="<?php echo $row['id']; ?>"
                                            data-username="<?php echo htmlspecialchars($row['username']); ?>"
                                            data-email="<?php echo htmlspecialchars($row['email']); ?>"
                                            data-phone="<?php echo htmlspecialchars($row['phone'] ?? ''); ?>"
                                            data-role="<?php echo $row['role']; ?>">
                                            Sửa
                                        </button>
                                        <button class="btn-toggle"
                                            data-id="<?php echo $row['id']; ?>"
                                            data-status="<?php echo $row['status']; ?>">
                                            <?php echo $row['status'] === 'active' ? 'Khóa' : 'Mở'; ?>
                                        </button>
                                        <button class="btn-delete" data-id="<?php echo $row['id']; ?>">
                                            Xóa
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>


        <!-- Modal Thêm -->
        <div id="addModal" class="modal hidden">
            <div class="modal-content">
                <h3>Thêm Tài Khoản Mới</h3>
                <form id="addForm">
                    <div>
                        <label for="addUsername">Tài Khoản:</label>
                        <input type="text" id="addUsername" name="username" required>
                    </div>
                    <div>
                        <label for="addEmail">Email:</label>
                        <input type="email" id="addEmail" name="email" required>
                    </div>
                    <div>
                        <label for="addPassword">Mật Khẩu:</label>
                        <input type="password" id="addPassword" name="password" required>
                    </div>
                    <div>
                        <label for="addRole">Vai Trò:</label>
                        <select id="addRole" name="role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="modal-buttons">
                        <button type="submit">Lưu</button>
                        <button type="button" onclick="closeModal('addModal')">Hủy</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Sửa -->
        <div id="editModal" class="modal hidden">
            <div class="modal-content">
                <h3>Chỉnh Sửa Tài Khoản</h3>
                <form id="editForm">
                    <input type="hidden" id="editId" name="id">
                    <div>
                        <label for="editUsername">Tài Khoản:</label>
                        <input type="text" id="editUsername" name="username" disabled>
                    </div>
                    <div>
                        <label for="editEmail">Email:</label>
                        <input type="email" id="editEmail" name="email" required>
                    </div>
                    <div>
                        <label for="editPhone">Số Điện Thoại:</label>
                        <input type="tel" id="editPhone" name="phone">
                    </div>
                    <div>
                        <label for="editPassword">Mật Khẩu Mới (để trống nếu không đổi):</label>
                        <input type="password" id="editPassword" name="password">
                    </div>
                    <div>
                        <label for="editRole">Vai Trò:</label>
                        <select id="editRole" name="role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="modal-buttons">
                        <button type="submit">Lưu thay đổi</button>
                        <button type="button" onclick="closeModal('editModal')">Hủy</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Xóa -->
        <div id="deleteModal" class="modal hidden">
            <div class="modal-content">
                <h3>Xác Nhận Xóa</h3>
                <p>Bạn có chắc chắn muốn xóa tài khoản này? Hành động này không thể hoàn tác.</p>
                <form id="deleteForm">
                    <input type="hidden" id="deleteId" name="id">
                    <div class="modal-buttons">
                        <button type="submit" class="btn-danger">Xóa</button>
                        <button type="button" onclick="closeModal('deleteModal')">Hủy</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Khóa/Mở -->
        <div id="toggleModal" class="modal hidden">
            <div class="modal-content">
                <h3>Xác Nhận Thay Đổi</h3>
                <p>Bạn có chắc chắn muốn thay đổi trạng thái của tài khoản này?</p>
                <form id="toggleForm">
                    <input type="hidden" id="toggleId" name="id">
                    <input type="hidden" id="toggleStatus" name="status">
                    <div class="modal-buttons">
                        <button type="submit">Xác nhận</button>
                        <button type="button" onclick="closeModal('toggleModal')">Hủy</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <?php require_once __DIR__ . '/layouts/footer.php'; ?>
    </div>

    <script src="assets/js/user.js"></script>
    <script src="assets/js/headerFooter.js"></script>
    <script src="assets/js/toast.js"></script>
</body>

</html>