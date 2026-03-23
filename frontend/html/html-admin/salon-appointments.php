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
                
                <form method="GET" action="" class="filter-bar" style="display: flex; gap: 15px; align-items: center; margin-bottom: 25px;">
                    <div>
                        <label for="filter-date" style="font-weight: bold;">Ngày:</label>
                        <input type="date" id="filter-date" name="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
                    </div>
                    
                    <div>
                        <label for="filter-status" style="font-weight: bold;">Trạng thái:</label>
                        <select id="filter-status" name="status" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
                            <option value="">Tất cả</option>
                            <option value="pending" <?php echo (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'selected' : ''; ?>>Chờ xác nhận</option>
                            <option value="confirmed" <?php echo (isset($_GET['status']) && $_GET['status'] == 'confirmed') ? 'selected' : ''; ?>>Đã xác nhận</option>
                            <option value="completed" <?php echo (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'selected' : ''; ?>>Đã hoàn thành</option>
                            <option value="cancelled" <?php echo (isset($_GET['status']) && $_GET['status'] == 'cancelled') ? 'selected' : ''; ?>>Đã hủy</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn primary-btn"><i class="fas fa-filter"></i> Lọc</button>
                    
                    <?php if(!empty($_GET['date']) || !empty($_GET['status'])): ?>
                        <a href="salon-appointments.php" class="btn secondary-btn" style="text-decoration: none;">Hủy lọc</a>
                    <?php endif; ?>
                </form>

                <div class="appointment-list">
                    <?php
                    require_once '../../../backend/db_connect.php';
                    
                    // Câu lệnh cơ bản có dùng WHERE 1=1 để nối điều kiện lọc
                    $sql = "SELECT a.*, u.full_name as cust_name, u.phone as cust_phone, 
                                   s.service_name, b.branch_name, st_u.full_name as stylist_name
                            FROM appointments a
                            JOIN users u ON a.customer_id = u.user_id
                            JOIN services s ON a.service_id = s.service_id
                            JOIN branches b ON a.branch_id = b.branch_id
                            LEFT JOIN stylists st ON a.stylist_id = st.stylist_id
                            LEFT JOIN users st_u ON st.user_id = st_u.user_id
                            WHERE 1=1";
                            
                    $params = [];

                    if (!empty($_GET['date'])) {
                        $sql .= " AND DATE(a.appointment_date) = ?";
                        $params[] = $_GET['date'];
                    }

                    if (!empty($_GET['status'])) {
                        $sql .= " AND a.status = ?";
                        $params[] = $_GET['status'];
                    }

                    $sql .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";
                            
                    $stmt = $conn->prepare($sql);
                    $stmt->execute($params);
                    $appointments = $stmt->fetchAll();

                    if (count($appointments) > 0):
                        foreach ($appointments as $app):
                            $status_class = $app['status']; 
                            $status_text = 'Không xác định';
                            if ($app['status'] == 'pending') $status_text = 'Chờ xác nhận';
                            if ($app['status'] == 'confirmed') $status_text = 'Đã xác nhận';
                            if ($app['status'] == 'completed') $status_text = 'Đã hoàn thành';
                            if ($app['status'] == 'cancelled') $status_text = 'Đã hủy';
                    ?>
                        <div class="appointment-item <?php echo $status_class; ?>" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 8px;">
                            <div class="appointment-details">
                                <h3 style="margin-top: 0; color: #333;">Khách hàng: <?php echo htmlspecialchars($app['cust_name']); ?></h3>
                                <p><strong>SĐT:</strong> <?php echo htmlspecialchars($app['cust_phone']); ?></p>
                                <p><strong>Thời gian:</strong> <?php echo date('d/m/Y', strtotime($app['appointment_date'])); ?>, <?php echo substr($app['appointment_time'], 0, 5); ?></p>
                                <p><strong>Dịch vụ:</strong> <?php echo htmlspecialchars($app['service_name']); ?></p>
                                <p><strong>Thợ cắt:</strong> <?php echo htmlspecialchars($app['stylist_name'] ?? 'Không chọn'); ?></p>
                                <p><strong>Ghi chú:</strong> <?php echo htmlspecialchars($app['notes']); ?></p>
                                <p class="appointment-status">Trạng thái: 
                                    <span style="font-weight: bold; color: <?php 
                                        echo ($app['status'] == 'completed') ? 'blue' : 
                                            (($app['status'] == 'confirmed') ? 'green' : 
                                            (($app['status'] == 'cancelled') ? 'red' : 'orange')); 
                                    ?>;"><?php echo $status_text; ?></span>
                                </p>
                            </div>
                            
                            <div class="appointment-actions" style="margin-top: 10px;">
                                <?php if($app['status'] == 'pending'): ?>
                                    <button class="btn confirm-btn" style="background-color: #4CAF50; color: white;" onclick="updateStatus(<?php echo $app['appointment_id']; ?>, 'confirmed')">
                                        <i class="fas fa-check"></i> Xác nhận
                                    </button>
                                    <button class="btn reject-btn" style="background-color: #f44336; color: white;" onclick="updateStatus(<?php echo $app['appointment_id']; ?>, 'cancelled')">
                                        <i class="fas fa-times"></i> Hủy
                                    </button>
                                <?php elseif($app['status'] == 'confirmed'): ?>
                                    <button class="btn" style="background-color: #2196F3; color: white;" onclick="updateStatus(<?php echo $app['appointment_id']; ?>, 'completed')">
                                        <i class="fas fa-flag-checkered"></i> Hoàn thành
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php 
                        endforeach; 
                    else: 
                        echo "<div class='no-items' style='text-align: center; padding: 20px;'>Không tìm thấy lịch hẹn nào.</div>";
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