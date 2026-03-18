<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
require_once '../../../backend/db_connect.php';

// LẤY THÔNG TIN CÁ NHÂN TỪ DATABASE
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT full_name, phone, email, created_at FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Của Tôi - Barber Shop</title>
    <link rel="stylesheet" href="../../css/css-client/style.css">
    <link rel="stylesheet" href="../../css/css-client/my-profile-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body> 
    <header class="site-header">
        <div class="container header-container">
            <div class="logo"><img src="../../image/logo.png" alt="Barber Shop Logo"></div>
            <nav class="desktop-nav">
                <ul>
                    <li><a href="index.php">Trang chủ</a></li>
                    <li><a href="about.html">Về chúng tôi</a></li> 
                    <li><a href="services.html">Dịch vụ</a></li> 
                </ul>      
            </nav>
            <div class="header-buttons desktop-buttons">
                <a href="appointment.php" class="btn primary-btn">Đặt lịch hẹn</a> 
            </div>
            <div class="header-buttons desktop-buttons">
                <div class="user-account">
                    <a href="#" class="user-icon-link" style="color: #ff7f00;"> 
                        <i class="fas fa-user-circle"></i>
                        <span>Xin chào, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                        <i class="fas fa-chevron-down" style="font-size: 0.8rem; margin-left: 5px;"></i>
                    </a>
                    <div class="account-dropdown"> 
                        <a href="my-profile.php">Tài khoản của tôi</a>
                        <a href="history.php">Lịch sử đặt lịch</a>
                        <a href="../../../backend/logout.php" style="color: red !important;">Đăng xuất</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="page-main">
        <div class="container">
            <div class="profile-card">
                <h2>Thông Tin Cá Nhân Của Tôi</h2> 
                <form id="profile-form">
                    <div class="form-group">
                        <label>Tên Khách hàng:</label>
                        <input type="text" readonly style="background-color: #f0f0f0;" 
                               value="<?php echo htmlspecialchars($user['full_name']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại:</label>
                        <input type="tel" readonly style="background-color: #f0f0f0;" 
                               value="<?php echo htmlspecialchars($user['phone']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" readonly style="background-color: #f0f0f0;" 
                               value="<?php echo htmlspecialchars($user['email'] ?? 'Chưa cập nhật'); ?>"> 
                    </div>
                    <div class="form-group">
                        <label>Ngày đăng ký:</label>
                        <input type="text" readonly style="background-color: #f0f0f0;" 
                               value="<?php echo date('d-m-Y H:i', strtotime($user['created_at'])); ?>">
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer style="background-color: #1a1a1a; color: #fff; text-align: center; padding: 20px;">
         <p> Barber Shop. All rights reserved.</p>
    </footer>
    <script src="../../js/js-client/script.js"></script>
</body>
</html>