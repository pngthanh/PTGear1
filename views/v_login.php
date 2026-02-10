<?php

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/login.css">
    <title>Đăng Nhập</title>
</head>


<body>
    <img class="wave" src="assets/img/wave.png" alt="Wave">
    <div class="container">
        <div class="img">
            <img src="assets/img/bg.svg" alt="Hình Đăng Nhập">
        </div>
        <div class="login-content">
            <form method="POST" action="index.php?page=login">
                <img src="assets/img/avatar.svg" alt="Avatar">
                <h2 class="title">Đăng Nhập</h2>

                <?php if (!empty($error)): ?>
                    <div id="errorMessage" class="active"><?php echo htmlspecialchars($error); ?></div>
                <?php else: ?>
                    <div id="errorMessage"></div>
                <?php endif; ?>

                <div class="input-div one">
                    <div class="i">
                        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                    </div>
                    <div class="div">
                        <h5>Tài Khoản</h5>
                        <input type="text" class="input" name="username">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </div>
                    <div class="div">
                        <h5>Mật Khẩu</h5>
                        <input type="password" class="input" name="password">
                    </div>
                </div>
                <div class="link-container">
                    <a href="index.php?page=forgot-password">Quên Mật Khẩu?</a>
                    <a href="index.php?page=register">Đăng Ký</a>
                </div>
                <input type="submit" class="btn" value="Đăng Nhập">
                <p>Đăng nhập bằng tài khoản khác</p>
                <div class="social-icons">
                    <a href="#"><i class="fa fa-google" aria-hidden="true"></i></a>
                    <a href="#"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>
                    <a href="#"><i class="fa fa-github" aria-hidden="true"></i></a>
                    <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/login.js"></script>
</body>

</html>