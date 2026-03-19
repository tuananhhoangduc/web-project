<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý Chi nhánh - Barber Shop Admin</title>
    <link rel="stylesheet" href="../../css/css-admin/style.css" />
    <link rel="stylesheet" href="../../css/css-admin/branch-management-styles.css" />
    <link rel="stylesheet" href="../../css/css-admin/admin-dashboard-styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
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
        <h1>Quản lý Chi nhánh</h1>

        <div class="branch-management-container">
          <div class="add-branch-block">
            <h2>Thêm Chi nhánh Mới</h2>

            <button class="btn primary-btn" id="show-add-branch-form-btn">
              <i class="fas fa-plus-square"></i> Thêm Chi nhánh
            </button>

            <div id="add-branch-form-container" class="add-branch-form-container">
              <form id="add-branch-form">
                  <input type="hidden" id="branch-id" name="branch_id" value="">

                  <div class="form-group">
                      <label for="branch-name">Tên Chi nhánh:</label>
                      <input type="text" id="branch-name" name="branch_name" placeholder="Nhập tên chi nhánh" required />
                  </div>
                  
                  <div class="form-group">
                      <label for="branch-address">Địa chỉ:</label>
                      <input type="text" id="branch-address" name="branch_address" placeholder="Nhập địa chỉ chi nhánh" required />
                  </div>
                  
                  <div class="form-group">
                      <label for="branch-phone">Số điện thoại:</label>
                      <input type="tel" id="branch-phone" name="branch_phone" placeholder="Nhập SĐT chi nhánh" />
                  </div>
                  
                  <div class="form-group">
                      <label for="branch-email">Email:</label>
                      <input type="email" id="branch-email" name="branch_email" placeholder="Nhập email chi nhánh" />
                  </div>

                  <button type="submit" id="submit-btn" class="btn primary-btn">
                      <i class="fas fa-save"></i> Lưu Chi nhánh
                  </button>
                  <button type="button" class="btn secondary-btn" id="cancel-add-branch-form-btn">
                      <i class="fas fa-times"></i> Hủy
                  </button>
              </form>
            </div>
          </div>

          <div class="branch-list">
            <h2>Danh sách Chi nhánh</h2>
            <table>
              <thead>
                <tr>
                  <th>Mã Chi nhánh</th>
                  <th>Tên Chi nhánh</th>
                  <th>Địa chỉ</th>
                  <th>Số điện thoại</th>
                  <th>Email</th>
                  <th>Hành động</th>
                </tr>
              </thead>
              <tbody>
                  <?php
                  require_once '../../../backend/db_connect.php';
                  
                  $sql = "SELECT * FROM branches ORDER BY branch_id ASC";
                  $stmt = $conn->query($sql);
                  $branches = $stmt->fetchAll();

                  if (count($branches) > 0):
                      foreach ($branches as $b):
                  ?>
                      <tr>
                          <td>CN<?php echo str_pad($b['branch_id'], 3, '0', STR_PAD_LEFT); ?></td>
                          <td><?php echo htmlspecialchars($b['branch_name']); ?></td>
                          <td><?php echo htmlspecialchars($b['address']); ?></td>
                          <td><?php echo htmlspecialchars($b['phone']); ?></td>
                          <td><?php echo htmlspecialchars($b['email'] ?? 'Chưa cập nhật'); ?></td>
                          <td>
                              <button class="btn edit-btn" 
                                  onclick="editBranch(this)"
                                  data-id="<?php echo $b['branch_id']; ?>"
                                  data-name="<?php echo htmlspecialchars($b['branch_name']); ?>"
                                  data-address="<?php echo htmlspecialchars($b['address']); ?>"
                                  data-phone="<?php echo htmlspecialchars($b['phone']); ?>"
                                  data-email="<?php echo htmlspecialchars($b['email']); ?>">
                                  <i class="fas fa-edit"></i> Sửa
                              </button>
                              <button class="btn delete-btn" onclick="deleteBranch(<?php echo $b['branch_id']; ?>)">
                                  <i class="fas fa-trash"></i> Xóa
                              </button>
                          </td>
                      </tr>
                  <?php 
                      endforeach; 
                  else: 
                      echo "<tr><td colspan='6' style='text-align:center;'>Chưa có chi nhánh nào.</td></tr>";
                  endif; 
                  ?>
              </tbody>
            </table>
            <div class="no-items"></div>
          </div>
        </div>
      </div>
    </main>

    <footer style="background-color: #1a1a1a; color: #fff; text-align: center; padding: 20px;">
      <p>&copy; 2026 Barber Shop. All rights reserved.</p>
    </footer>

    <script src="../../js/js-client/script.js"></script>
    <script src="../../js/js-admin/branch-management-script.js"></script>
    
    <script>
    // 1. XỬ LÝ NÚT XÓA BẰNG API
    function deleteBranch(id) {
        if (confirm('Bạn có chắc chắn muốn xóa chi nhánh này không? Dữ liệu không thể khôi phục!')) {
            fetch(`../../../backend/delete_branch.php?id=${id}`)
            .then(response => response.json())
            .then(result => {
                alert(result.message);
                if (result.status === 'success') location.reload();
            })
            .catch(error => console.error('Lỗi API:', error));
        }
    }

    // 2. BƠM DỮ LIỆU LÊN FORM SỬA (Giữ nguyên logic giao diện)
    function editBranch(button) {
        document.getElementById('branch-id').value = button.getAttribute('data-id');
        document.getElementById('branch-name').value = button.getAttribute('data-name');
        document.getElementById('branch-address').value = button.getAttribute('data-address');
        document.getElementById('branch-phone').value = button.getAttribute('data-phone');
        document.getElementById('branch-email').value = button.getAttribute('data-email');

        document.querySelector('.add-branch-block h2').innerText = 'Cập nhật Chi nhánh'; 
        document.getElementById('submit-btn').innerHTML = '<i class="fas fa-check"></i> Cập nhật ngay';

        document.getElementById('add-branch-form-container').style.display = 'block';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // 3. XỬ LÝ KHI BẤM NÚT "LƯU" HOẶC "CẬP NHẬT" (SUBMIT FORM)
    document.getElementById('add-branch-form').addEventListener('submit', function(e) {
        e.preventDefault(); // Chặn hành vi tải lại trang mặc định

        const id = document.getElementById('branch-id').value;
        const isUpdate = (id !== ''); 
        const apiUrl = isUpdate ? '../../../backend/update_branch.php' : '../../../backend/add_branch.php';

        const data = {
            branch_id: id,
            branch_name: document.getElementById('branch-name').value,
            branch_address: document.getElementById('branch-address').value,
            branch_phone: document.getElementById('branch-phone').value,
            branch_email: document.getElementById('branch-email').value
        };

        fetch(apiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            alert(result.message);
            if (result.status === 'success') location.reload();
        })
        .catch(error => console.error('Lỗi API:', error));
    });
    </script>
  </body>
</html>