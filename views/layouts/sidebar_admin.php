<?php

?>
<aside class="sidebar">
    <h3 class="sidebar-title">Menu Admin</h3>
    <ul class="sidebar-menu">

        <li class="<?php echo ($page === 'admin_dashboard') ? 'active' : ''; ?>">
            <a href="index.php?page=admin_dashboard"><i class="fa fa-bar-chart"></i> Thống kê</a>
        </li>
        <li class="<?php echo ($page === 'admin_users') ? 'active' : ''; ?>">
            <a href="index.php?page=admin_users"><i class="fa fa-user"></i> Quản lý tài khoản</a>
        </li>
        <li class="<?php echo ($page === 'admin_products') ? 'active' : ''; ?>">
            <a href="index.php?page=admin_products"><i class="fa fa-cubes"></i> Quản lý sản phẩm</a>
        </li>
        <li class="<?php echo ($page === 'admin_orders') ? 'active' : ''; ?>">
            <a href="index.php?page=admin_orders"><i class="fa fa-shopping-cart"></i> Quản lý đơn hàng</a>
        </li>
    </ul>
</aside>