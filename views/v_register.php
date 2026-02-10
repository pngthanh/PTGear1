<?php

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/register.css">
    <title>Đăng Ký</title>
</head>

<body>
    <img class="wave" src="assets/img/wave.png" alt="Wave">
    <div class="container">
        <div class="img">
            <img src="assets/img/bg.svg" alt="Hình Đăng Ký">
        </div>
        <div class="register-content">
            <form method="POST" action="index.php?page=register">
                <img src="assets/img/avatar.svg" alt="Avatar">
                <h2 class="title">ĐĂNG KÝ</h2>

                <?php if (!empty($error)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php elseif (!empty($success)): ?>
                    <div class="success-message">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <div class="input-div one">
                    <div class="i"><i class="fa fa-user-circle-o"></i></div>
                    <div class="div">
                        <h5>Tài Khoản</h5>
                        <input type="text" class="input" name="username"
                            value="<?php echo $clear_username ? '' : htmlspecialchars($post_data['username'] ?? ''); ?>">
                    </div>
                </div>
                <div class="input-div one">
                    <div class="i"><i class="fa fa-envelope"></i></div>
                    <div class="div">
                        <h5>Email</h5>
                        <input type="email" class="input" name="email"
                            value="<?php echo $clear_email ? '' : htmlspecialchars($post_data['email'] ?? ''); ?>">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i"><i class="fa fa-lock"></i></div>
                    <div class="div">
                        <h5>Mật Khẩu</h5>
                        <input type="password" class="input" name="password"
                            value="<?php echo $clear_password ? '' : htmlspecialchars($post_data['password'] ?? ''); ?>">
                    </div>
                </div>
                <div class="link-container">
                    <a href="index.php?page=login">Đăng Nhập</a>
                </div>
                <input type="submit" class="btn" value="Đăng Ký">
                <p>Đăng ký bằng tài khoản khác</p>
                <div class="social-icons">
                    <a href="#"><i class="fa fa-google"></i></a>
                    <a href="#"><i class="fa fa-facebook-square"></i></a>
                    <a href="#"><i class="fa fa-github"></i></a>
                    <a href="#"><i class="fa fa-linkedin"></i></a>
                </div>
            </form>
        </div>
    </div>
    <script>
        const clearAll = <?php echo ($error || $success) ? 'true' : 'false'; ?>;
    </script>
    <script src="assets/js/register.js"></script>
</body>

</html>