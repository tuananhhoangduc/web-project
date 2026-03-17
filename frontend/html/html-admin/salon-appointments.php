<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Lịch hẹn - Barber Shop Admin</title>
    <link rel="stylesheet" href="../../css/css-admin/style.css">
    <link rel="stylesheet" href="../../css/css-admin/salon-appointments-styles.css">
    <link rel="stylesheet" href="../../css/css-admin/https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
      <header class="site-header">
        <div class="container header-container">
             <div class="logo">
                 <img src="../../image/logo.png" alt="Barber Shop Logo">
             </div>
             <div class="hamburger-icon">
                 <i class="fas fa-bars"></i>
             </div>
             <div class="mobile-menu-overlay">
                  <div class="mobile-menu-content">
                      <div class="close-icon">
                          <i class="fas fa-times"></i>
                      </div>
                      <nav class="mobile-nav">
                          <ul>
                              <li><a href="admin-dashboard.html">Trang chủ</a></li>
                              <li><a href="customer-management.php">Khách hàng</a></li>
                              <li><a href="salon-appointments.php">Lịch hẹn</a></li> 
                              <li><a href="branch-management.php">Chi nhánh</a></li> 
                              <li><a href="service-management.php">Dịch vụ</a></li> 
                              <li><a href="stylist-management.php">Stylist</a></li>


                          </ul>
                      </nav>
                  </div>
             </div>
             <nav class="desktop-nav">
                   <ul>
                           <li><a href="admin-dashboard.html">Trang chủ</a></li>
                              <li><a href="customer-management.php">Khách hàng</a></li>
                              <li><a href="salon-appointments.php">Lịch hẹn</a></li> 
                              <li><a href="branch-management.php">Chi nhánh</a></li> 
                              <li><a href="service-management.php">Dịch vụ</a></li> 
                              <li><a href="stylist-management.php">Stylist</a></li>

                   </ul>
             </nav>
         </div>
    </header>

   <main class="page-main"> 
        <div class="container">
            <h1>Quản lý Lịch hẹn Salon</h1>

            <div class="appointment-management-container">
                <div class="filter-bar">
                   <input type="date">, <select> trạng thái, nút tìm kiếm
                   <label for="filter-date">Ngày:</label>
                    <input type="date" id="filter-date">
                    <label for="filter-status">Trạng thái:</label>
                    <select id="filter-status">
                        <option value="">Tất cả</option>
                        <option value="pending">Chờ xác nhận</option>
                        <option value="confirmed">Đã xác nhận</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                    <button class="btn">Lọc</button> 
                </div>

                <div class="appointment-list">
    <?php
    require_once '../../../backend/db_connect.php';
    
    // Câu lệnh lấy dữ liệu lịch hẹn kèm tên khách, thợ, dịch vụ
    $sql = "SELECT a.*, u.full_name as cust_name, u.phone as cust_phone, 
                   s.service_name, b.branch_name, st_u.full_name as stylist_name
            FROM appointments a
            JOIN users u ON a.customer_id = u.user_id
            JOIN services s ON a.service_id = s.service_id
            JOIN branches b ON a.branch_id = b.branch_id
            LEFT JOIN stylists st ON a.stylist_id = st.stylist_id
            LEFT JOIN users st_u ON st.user_id = st_u.user_id
            ORDER BY a.appointment_date DESC, a.appointment_time DESC";
            
    $stmt = $conn->query($sql);
    $appointments = $stmt->fetchAll();

    if (count($appointments) > 0):
        foreach ($appointments as $app):
            // Xác định class CSS dựa trên trạng thái
            $status_class = $app['status']; // pending, confirmed, cancelled...
            $status_text = ($app['status'] == 'pending') ? 'Chờ xác nhận' : 
                           (($app['status'] == 'confirmed') ? 'Đã xác nhận' : 'Đã hủy');
    ?>
        <div class="appointment-item <?php echo $status_class; ?>">
            <div class="appointment-details">
                <h3>Khách hàng: <?php echo $app['cust_name']; ?></h3>
                <p><strong>SĐT:</strong> <?php echo $app['cust_phone']; ?></p>
                <p><strong>Thời gian:</strong> <?php echo date('d/m/Y', strtotime($app['appointment_date'])); ?>, <?php echo substr($app['appointment_time'], 0, 5); ?></p>
                <p><strong>Dịch vụ:</strong> <?php echo $app['service_name']; ?></p>
                <p><strong>Thợ cắt:</strong> <?php echo $app['stylist_name'] ?? 'Không chọn'; ?></p>
                <p><strong>Ghi chú:</strong> <?php echo $app['notes']; ?></p>
                <p class="appointment-status">Trạng thái: <span><?php echo $status_text; ?></span></p>
            </div>
            <div class="appointment-actions">
                <?php if($app['status'] == 'pending'): ?>
                    <button class="btn confirm-btn" onclick="updateStatus(<?php echo $app['appointment_id']; ?>, 'confirmed')">
                        <i class="fas fa-check"></i> Xác nhận
                    </button>
                    <button class="btn reject-btn" onclick="updateStatus(<?php echo $app['appointment_id']; ?>, 'cancelled')">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                <?php endif; ?>
                <button class="btn secondary-btn"><i class="fas fa-info-circle"></i> Chi tiết</button>
            </div>
        </div>
    <?php 
        endforeach; 
    else: 
        echo "<div class='no-items'>Hiện chưa có lịch hẹn nào.</div>";
    endif; 
    ?>
</div>

<script>
function updateStatus(id, status) {
    if(confirm('Bạn có chắc chắn muốn thay đổi trạng thái lịch hẹn này?')) {
        window.location.href = '../../../backend/update_appointment_status.php?id=' + id + '&status=' + status;
    }
}
</script>
            </div>

        </div>
    </main>

    <footer style="background-color: #1a1a1a; color: #fff; text-align: center; padding: 20px;">
         <p>Barber Shop. All rights reserved.</p>
     </footer>

    <script src="../../js/js-client/script.js"></script>
    <script src="../../js/js-admin/salon-appointments-script.js"></script>
</body>
</html>