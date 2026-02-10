<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['user_role'] ?? 'guest';
$username = $_SESSION['username'] ?? 'Khách';
?>
<header class="header">
    <div class="container">
        <!-- Logo -->
        <div class="logo">
            <div class="logo-section">
                <a href="index.php?page=home"><img src="assets/img/logo1.png" alt="PTGear"></a>
            </div>
        </div>

        <!-- Menu -->
        <div class="menu-section">
            <div class="menu">
                <a href="index.php?page=home" class="menu-item">TRANG CHỦ</a>
                <a href="index.php?page=products&category=1" class="menu-item">PHỤ KIỆN</a>
                <a href="index.php?page=products&category=2" class="menu-item">LINH KIỆN</a>
                <a href="index.php?page=lienhe" class="menu-item">LIÊN HỆ</a>
                <span class="menu-indicator"></span>
            </div>
        </div>

        <!-- Others -->
        <div class="others-section">
            <div class="others">
                <!-- search -->
                <div class="search">
                    <input
                        type="text"
                        placeholder="Tìm kiếm"
                        class="search-input" />
                    <i class="fa fa-search search-icon"></i>
                </div>

                <!-- dropdown user -->
                <div class="user-dropdown">
                    <a href="#" class="other-item"><i class="fa fa-user-circle-o"></i>
                    </a>
                    <div class="dropback-menu">
                    <?php if ($role === 'admin'): ?>

                        <li><a href="index.php?page=admin_dashboard"><i class="fa fa-dashboard"></i> TRANG QUẢN LÝ</a></li>
                        <li><a href="index.php?page=profile"><i class="fa fa-user"></i> Tài khoản của tôi</a></li>
                        <li><a href="index.php?page=orders"><i class="fa fa-shopping-bag"></i> Đơn hàng của tôi</a></li>
                        <li><a href="index.php?page=logout"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>

                    <?php elseif ($role === 'user'): ?>
                        <li><a href="index.php?page=profile"><i class="fa fa-user"></i> Tài khoản của tôi</a></li>
                        <li><a href="index.php?page=orders"><i class="fa fa-shopping-bag"></i> Đơn hàng của tôi</a></li>
                        <li><a href="index.php?page=logout"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
                        
                    <?php else: // guest ?>
                        <li><a href="index.php?page=login"><i class="fa fa-sign-in"></i> Đăng nhập</a></li>
                        <li><a href="index.php?page=register"><i class="fa fa-user-plus"></i> Đăng ký</a></li>
                    <?php endif; ?>
                </div>
                </div>

                <!-- cart -->
                <a href="index.php?page=cart" class="other-item"><i class="fa fa-cart-plus"></i></a>
            </div>
        </div>
    </div>
</header>