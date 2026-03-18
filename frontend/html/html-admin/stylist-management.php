<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý Stylist - Barber Shop Admin</title>
    <link rel="stylesheet" href="../../css/css-admin/style.css" />
    <link
      rel="stylesheet"
      href="../../css/css-admin/stylist-management-styles.css"
    />
    <link
      rel="stylesheet"
      href="../../css/css-admin/admin-dashboard-styles.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
  </head>
  <body>
    <header class="site-header">
      <div class="container header-container">
        <div class="logo">
          <img src="../../image/logo.png" alt="Barber Shop Logo" />
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
        <h1>Quản lý Stylist</h1>

        <div class="stylist-management-container">
          <div class="add-stylist-block">
            <h2>Thêm Stylist Mới</h2>

            <button class="btn primary-btn" id="show-add-stylist-form-btn">
              <i class="fas fa-user-plus"></i> Thêm Stylist
            </button>

            <div
              id="add-stylist-form-container"
              class="add-stylist-form-container"
            >
              <form id="add-stylist-form" action="../../../backend/add_stylist.php" method="POST">
    <input type="hidden" id="stylist-id" name="stylist_id" value="">

    <div class="form-group">
        <label for="stylist-name">Tên Thợ:</label>
        <input type="text" id="stylist-name" name="stylist_name" placeholder="Nhập tên thợ" required />
    </div>
    
    <div class="form-group">
        <label for="stylist-phone">Số điện thoại:</label>
        <input type="tel" id="stylist-phone" name="stylist_phone" placeholder="Nhập SĐT của thợ" required />
    </div>

    <div class="form-group">
        <label for="stylist-branch">Thuộc Chi nhánh:</label>
        <select id="stylist-branch" name="branch_id" required>
            <option value="">-- Chọn Chi nhánh --</option>
            <?php
            require_once '../../../backend/db_connect.php';
            $branches = $conn->query("SELECT branch_id, branch_name FROM branches")->fetchAll();
            foreach ($branches as $br) {
                echo "<option value='".$br['branch_id']."'>".$br['branch_name']."</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="stylist-status">Trạng thái:</label>
        <select id="stylist-status" name="stylist_status">
            <option value="active">Đang làm việc</option>
            <option value="inactive">Nghỉ phép</option>
        </select>
    </div>

    <button type="submit" class="btn primary-btn" id="submit-btn">
        <i class="fas fa-save"></i> Lưu Thợ Cắt
    </button>
    <button type="button" class="btn secondary-btn" id="cancel-add-stylist-form-btn">
        <i class="fas fa-times"></i> Hủy
    </button>
</form>
            </div>
          </div>

          <div class="stylist-list">
            <h2>Danh sách Stylist</h2>
            <table>
              <thead>
                <tr>
                  <th>Mã Stylist</th>
                  <th>Tên Stylist</th>
                  <th>Chi nhánh</th>
                  <th>Số điện thoại</th>
                  <th>Trạng thái</th>
                  <th>Hành động</th>
                </tr>
              </thead>
<tbody>
    <?php
    require_once '../../../backend/db_connect.php';

    // Câu lệnh SQL mới: Nối 3 bảng để lấy đủ Tên, SĐT và Tên Chi nhánh
    $sql = "SELECT s.stylist_id, s.branch_id, s.status, 
                   u.full_name, u.phone, 
                   b.branch_name 
            FROM stylists s 
            INNER JOIN users u ON s.user_id = u.user_id 
            LEFT JOIN branches b ON s.branch_id = b.branch_id 
            ORDER BY s.stylist_id DESC";
    $stmt = $conn->query($sql);
    $stylists = $stmt->fetchAll();

    if (count($stylists) > 0):
        foreach ($stylists as $st):
    ?>
        <tr>
            <td>ST<?php echo str_pad($st['stylist_id'], 3, '0', STR_PAD_LEFT); ?></td>
            
            <td><?php echo htmlspecialchars($st['full_name']); ?></td>
            
            <td><?php echo htmlspecialchars($st['branch_name'] ?? 'Chưa phân bổ'); ?></td>
            
            <td><?php echo htmlspecialchars($st['phone']); ?></td>
            
            <td>
                <span style="color: <?php echo ($st['status'] == 'Đang làm việc') ? 'green' : 'red'; ?>; font-weight: bold;">
                    <?php echo htmlspecialchars($st['status']); ?>
                </span>
            </td>
            
            <td>
                <button class="btn edit-btn" 
                    onclick="editStylist(this)"
                    data-id="<?php echo $st['stylist_id']; ?>"
                    data-name="<?php echo htmlspecialchars($st['full_name']); ?>"
                    data-phone="<?php echo htmlspecialchars($st['phone']); ?>"
                    data-branch="<?php echo $st['branch_id']; ?>"
                    data-status="<?php echo $st['status']; ?>"
                    style="background-color: #FFC107; color: #000;">
                    <i class="fas fa-edit"></i> Sửa
                </button>
                
                <button class="btn delete-btn" onclick="deleteStylist(<?php echo $st['stylist_id']; ?>)">
                    <i class="fas fa-trash"></i> Xóa
                </button>
            </td>
        </tr>
    <?php 
        endforeach; 
    else: 
        echo "<tr><td colspan='6' style='text-align:center;'>Chưa có thợ cắt nào.</td></tr>";
    endif; 
    ?>
</tbody>
            </table>
            <div class="no-items"></div>
          </div>
        </div>
      </div>
    </main>

    <footer
      style="
        background-color: #1a1a1a;
        color: #fff;
        text-align: center;
        padding: 20px;
      "
    >
      <p>Barber Shop. All rights reserved.</p>
    </footer>

    <script src="../../js/js-client/script.js"></script>
    <script src="../../js/js-admin/stylist-management-script.js"></script>
  </body>
</html>
