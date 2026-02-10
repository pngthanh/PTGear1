<?php
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/forgot-password.css">
    <title>Quên Mật Khẩu</title>
</head>

<body>
    <img class="wave" src="assets/img/wave.png" alt="Wave">
    <div class="container">
        <div class="img">
            <img src="assets/img/bg.svg" alt="Hình Quên mật khẩu">
        </div>
        <div class="login-content">
            <form method="POST" action="index.php?page=forgot-password">
                <img src="assets/img/avatar.svg" alt="Avatar">
                <h2 class="titles">Quên Mật Khẩu</h2>

                <?php if (!empty($message)): ?>
                    <div class="error-message" style="display:block; color:<?php echo $message_type === 'success' ? 'green' : 'red'; ?>; margin-bottom:15px;">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <div class="input-div one">
                    <div class="i"><i class="fa fa-envelope"></i></div>
                    <div class="div">
                        <h5>Email</h5>
                        <input type="email" class="input" name="email" required>
                    </div>
                </div>
                <div class="link-container">
                    <a href="index.php?page=login">Quay lại đăng nhập</a>
                </div>
                <input type="submit" class="btn" value="Gửi">
            </form>
        </div>
    </div>

    <script src="assets/js/forgot.js"></script>
</body>

</html>