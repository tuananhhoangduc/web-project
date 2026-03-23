<?php
session_start();
// Bắt buộc đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
require_once '../../../backend/db_connect.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Sử Đặt Hẹn - Barber Shop</title>
    <link rel="stylesheet" href="../../css/css-client/style.css">
    <link rel="stylesheet" href="../../css/css-client/history-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header class="site-header">
        <div class="container header-container">
            <div class="logo"><img src="../../image/logo.png" alt="Barber Shop Logo"></div>
            <nav class="desktop-nav">
                <ul>
                    <li><a href="index.php">Trang chủ</a></li>
                    <li><a href="about.php">Về chúng tôi</a></li> 
                    <li><a href="services.php">Dịch vụ</a></li> 
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
            <h1>Lịch Sử Đặt Hẹn</h1>
            <p class="customer-history-info">Danh sách các lịch hẹn của bạn:</p>

            <div class="appointment-history-list">
                <?php
                // LẤY DỮ LIỆU LỊCH HẸN CỦA KHÁCH HÀNG ĐANG ĐĂNG NHẬP
                $user_id = $_SESSION['user_id'];
                $sql = "SELECT a.*, s.service_name, b.branch_name, u_stylist.full_name AS stylist_name
                        FROM appointments a
                        JOIN services s ON a.service_id = s.service_id
                        JOIN branches b ON a.branch_id = b.branch_id
                        LEFT JOIN stylists st ON a.stylist_id = st.stylist_id
                        LEFT JOIN users u_stylist ON st.user_id = u_stylist.user_id
                        WHERE a.customer_id = ?
                        ORDER BY a.appointment_date DESC, a.appointment_time DESC";
                
                $stmt = $conn->prepare($sql);
                $stmt->execute([$user_id]);
                $appointments = $stmt->fetchAll();

                if (count($appointments) > 0):
                    foreach ($appointments as $app):
                        // Dịch trạng thái sang tiếng Việt và chỉnh màu sắc
                        $status_color = '#f39c12'; // Màu cam mặc định cho pending
                        $status_text = 'Chờ xác nhận';
                        if ($app['status'] == 'confirmed') { $status_text = 'Đã xác nhận'; $status_color = '#27ae60'; }
                        if ($app['status'] == 'completed') { $status_text = 'Đã hoàn thành'; $status_color = '#2980b9'; }
                        if ($app['status'] == 'cancelled') { $status_text = 'Đã hủy'; $status_color = '#c0392b'; }
                ?>
                    <div class="appointment-history-item" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 8px;">
                        <div class="appointment-details-card">
                            <p><strong>Salon:</strong> <?php echo htmlspecialchars($app['branch_name']); ?></p>
                            <p><strong>Dịch Vụ:</strong> <?php echo htmlspecialchars($app['service_name']); ?> (<?php echo number_format($app['total_price'], 0, ',', '.'); ?> VNĐ)</p>
                            <p><strong>Thợ Cắt:</strong> <?php echo htmlspecialchars($app['stylist_name'] ?? 'Chưa sắp xếp'); ?></p>
                            <p><strong>Thời Gian:</strong> <?php echo date('d-m-Y', strtotime($app['appointment_date'])); ?>, <?php echo substr($app['appointment_time'], 0, 5); ?></p>
                            <p><strong>Ghi Chú:</strong> <?php echo htmlspecialchars($app['notes'] ?: 'Không có ghi chú'); ?></p>
                            <p class="appointment-status-card" style="color: <?php echo $status_color; ?>; font-weight: bold;">
                                Trạng Thái: <?php echo $status_text; ?>
                            </p>
                        </div>
                        
                        <?php if($app['status'] == 'pending'): ?>
                            <div style="margin-top: 10px;">
                                <a href="../../../backend/cancel_appointment_client.php?id=<?php echo $app['appointment_id']; ?>" 
                                   class="btn btn-cancel-appointment" 
                                   style="background-color: #e74c3c; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px;"
                                   onclick="return confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?');">Hủy Lịch</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php 
                    endforeach; 
                else: 
                    echo "<p style='text-align: center; color: #777;'>Bạn chưa có lịch hẹn nào.</p>";
                endif; 
                ?>
            </div>        
        </div>
    </main>

    <footer style="background-color: #1a1a1a; color: #fff; text-align: center; padding: 20px;">
        <p>Barber Shop. All rights reserved.</p>
    </footer>
    <script src="../../js/js-client/script.js"></script>
</body>
</html>