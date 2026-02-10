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
    <link rel="stylesheet" href="assets/css/profile.css">
    <title>Hồ Sơ Của Tôi - PTGear</title>
</head>

<body>
    <div class="page-wrapper">
        <!-- Header -->
        <?php require_once __DIR__ . '/layouts/header.php'; ?>

        <!-- Main Content: Hồ Sơ Của Tôi -->
        <div class="content">
            <div class="profile-container">
                <!-- Thông báo lỗi/thành công -->
                <?php if (!empty($error)): ?>
                    <div id="toast" class="toast error"><?= htmlspecialchars($error) ?></div>
                <?php elseif (!empty($success)): ?>
                    <div id="toast" class="toast success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <div class="profile-content">
                    <!-- Left Sidebar: Navigation Menu -->
                    <aside class="profile-sidebar">
                        <!-- User Info -->
                        <div class="sidebar-user-info">
                            <i class="fa fa-user-circle-o user-icon"></i>
                            <h3 class="user-name"><?= htmlspecialchars($user['fullname'] ?? $user['username']) ?></h3>
                        </div>

                        <!-- Navigation Menu -->
                        <nav class="sidebar-menu">
                            <a href="index.php?page=profile" class="menu-item active">
                                <i class="fa fa-user"></i>
                                <span>Tài khoản của tôi</span>
                            </a>
                            <a href="index.php?page=orders" class="menu-item">
                                <i class="fa fa-shopping-bag"></i>
                                <span>Đơn hàng của tôi</span>
                            </a>
                            <a href="index.php?page=logout" class="menu-item">
                                <i class="fa fa-sign-out"></i>
                                <span>Đăng xuất</span>
                            </a>
                        </nav>
                    </aside>

                    <!-- Right Section: Profile Forms -->
                    <div class="profile-main">
                        <!-- Page Header -->
                        <div class="profile-header">
                            <h1 class="profile-title">Hồ Sơ Của Tôi</h1>
                            <p class="profile-subtitle">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
                        </div>

                        <!-- Cards Container: 2 cột ngang -->
                        <div class="profile-cards-container">
                            <!-- Card 1: Thông tin cá nhân -->
                            <div class="profile-card">
                                <h2 class="card-title">Thông tin cá nhân</h2>
                                <form id="profileForm" class="profile-form" method="POST" action="index.php?page=profile">
                                    <input type="hidden" name="action" value="update_profile">
                                    <div class="form-group">
                                        <label for="username">Tên đăng nhập</label>
                                        <input
                                            type="text"
                                            id="username"
                                            name="username"
                                            value="<?= htmlspecialchars($user['username'] ?? '') ?>"
                                            disabled
                                            class="form-input">
                                    </div>

                                    <div class="form-group">
                                        <label for="fullname">Họ và tên</label>
                                        <input
                                            type="text"
                                            id="fullname"
                                            name="fullname"
                                            value="<?= htmlspecialchars($user['fullname'] ?? '') ?>"
                                            class="form-input"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input
                                            type="email"
                                            id="email"
                                            name="email"
                                            value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                                            class="form-input"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Số điện thoại</label>
                                        <input
                                            type="text"
                                            id="phone"
                                            name="phone"
                                            value="<?= isset($user['phone']) && $user['phone'] !== null ? htmlspecialchars($user['phone']) : '' ?>"
                                            class="form-input">
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn-save">Lưu Thay Đổi</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Card 2: Đổi mật khẩu -->
                            <div class="profile-card">
                                <h2 class="card-title">Đổi mật khẩu</h2>
                                <form id="passwordForm" class="profile-form" method="POST" action="index.php?page=profile">
                                    <input type="hidden" name="action" value="change_password">
                                    <div class="form-group">
                                        <label for="oldPassword">Mật khẩu cũ</label>
                                        <input
                                            type="password"
                                            id="oldPassword"
                                            name="oldPassword"
                                            class="form-input"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="newPassword">Mật khẩu mới</label>
                                        <input
                                            type="password"
                                            id="newPassword"
                                            name="newPassword"
                                            class="form-input"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="confirmPassword">Xác nhận mật khẩu mới</label>
                                        <input
                                            type="password"
                                            id="confirmPassword"
                                            name="confirmPassword"
                                            class="form-input"
                                            required>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn-change-password">Đổi Mật Khẩu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php require_once __DIR__ . '/layouts/footer.php'; ?>
    </div>

    <!-- Toast Notification -->
    <?php if (empty($error) && empty($success)): ?>
        <div id="toast" class="toast hidden"></div>
    <?php endif; ?>

    <script src="assets/js/profile.js"></script>
    <script src="assets/js/headerFooter.js"></script>
    <script src="assets/js/toast.js"></script>
</body>

</html>