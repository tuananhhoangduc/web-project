<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý Dịch vụ - Barber Shop Admin</title>
    <link rel="stylesheet" href="../../css/css-admin/style.css" />
    <link
      rel="stylesheet"
      href="../../css/css-admin/service-management-styles.css"
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
        <h1>Quản lý Dịch vụ</h1>

        <div class="service-management-container">
          <div class="add-service-block">
            <h2>Thêm Dịch vụ Mới</h2>
            <button class="btn primary-btn" id="show-add-service-form-btn">
              <i class="fas fa-plus"></i> Thêm Dịch vụ
            </button>

            <div
              id="add-service-form-container"
              class="add-service-form-container"
            >
              <form id="add-service-form" action="../../../backend/add_service.php" method="POST">
                <input type="hidden" id="service-id" name="service_id" value="">
                <div class="form-group">
                  <label for="service-name">Tên dịch vụ:</label>
                  <input
                    type="text"
                    id="service-name"
                    name="service_name"
                    placeholder="Nhập tên dịch vụ"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="service-price">Giá:</label>
                  <input
                    type="number"
                    id="service-price"
                    name="service_price"
                    placeholder="Nhập giá (VNĐ)"
                    required
                    min="0"
                  />
                </div>
                <div class="form-group">
                  <label for="service-duration">Thời lượng (phút):</label>
                  <input
                    type="number"
                    id="service-duration"
                    name="service_duration"
                    placeholder="Ví dụ: 30"
                    min="1"
                  />
                </div>
                <div class="form-group">
                  <label for="service-description">Mô tả:</label>
                  <textarea
                    id="service-description"
                    name="service_description"
                    rows="3"
                    placeholder="Mô tả dịch vụ (tùy chọn)"
                  ></textarea>
                </div>

                <button type="submit" class="btn primary-btn">
                  <i class="fas fa-save"></i> Lưu Dịch vụ
                </button>
                <button
                  type="button"
                  class="btn secondary-btn"
                  id="cancel-add-service-form-btn"
                >
                  <i class="fas fa-times"></i> Hủy
                </button>
              </form>
            </div>
          </div>

          <div class="service-list">
            <h2>Danh sách Dịch vụ</h2>
            <table>
              <thead>
                <tr>
                  <th>Mã Dịch vụ</th>
                  <th>Tên Dịch vụ</th>
                  <th>Giá</th>
                  <th>Thời lượng</th> 
                  <th>Mô tả</th>
                  <th>Hành động</th>
                </tr>
              </thead>
              <tbody>
                  <?php
                  require_once '../../../backend/db_connect.php';
                  
                  $sql = "SELECT * FROM services ORDER BY service_id DESC";
                  $stmt = $conn->query($sql);
                  $services = $stmt->fetchAll();

                  if (count($services) > 0):
                      foreach ($services as $s):
                  ?>
                      <tr>
                          <td>DV<?php echo str_pad($s['service_id'], 3, '0', STR_PAD_LEFT); ?></td>
                          <td><?php echo htmlspecialchars($s['service_name']); ?></td>
                          <td><?php echo number_format($s['price'], 0, ',', '.'); ?> VNĐ</td>
                          
                          <td><?php echo htmlspecialchars($s['duration'] ?? '0'); ?> phút</td>
                          
                          <td><?php echo htmlspecialchars($s['description'] ?? ''); ?></td>
                          
                          <td>
                              <button class="btn edit-btn" 
                                  onclick="editService(this)"
                                  data-id="<?php echo $s['service_id']; ?>"
                                  data-name="<?php echo htmlspecialchars($s['service_name']); ?>"
                                  data-price="<?php echo $s['price']; ?>"
                                  data-duration="<?php echo htmlspecialchars($s['duration'] ?? ''); ?>"
                                  data-desc="<?php echo htmlspecialchars($s['description'] ?? ''); ?>">
                                  <i class="fas fa-edit"></i> Sửa
                              </button>
                              
                              <button class="btn delete-btn" onclick="deleteService(<?php echo $s['service_id']; ?>)">
                                  <i class="fas fa-trash"></i> Xóa
                              </button>
                          </td>
                      </tr>
                  <?php 
                      endforeach; 
                  else: 
                      echo "<tr><td colspan='6' style='text-align:center;'>Chưa có dịch vụ nào.</td></tr>";
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
    <script src="../../js/js-admin/service-management-script.js"></script>
    <script>
      // Hàm xử lý Xóa dịch vụ
      function deleteService(id) {
          if (confirm('Bạn có chắc chắn muốn xóa dịch vụ này không?')) {
              window.location.href = '../../../backend/delete_service.php?id=' + id;
          }
      }

      // Hàm xử lý Sửa dịch vụ (Bơm dữ liệu ngược lên Form giống hệt bên Chi nhánh)
      function editService(button) {
    const id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const price = button.getAttribute('data-price');
    const duration = button.getAttribute('data-duration'); // Lấy thêm thời lượng
    const desc = button.getAttribute('data-desc');

    // Chèn vào các ô Input (Bạn nhớ kiểm tra xem id của các ô input trong form có đúng thế này không nhé)
    document.getElementById('service-id').value = id; // Ô hidden
    document.getElementById('service-name').value = name;
    document.getElementById('service-price').value = price;
    document.getElementById('service-duration').value = duration; // Ô thời lượng
    document.getElementById('service-desc').value = desc; 

    // Đổi Form sang Cập nhật
    const form = document.getElementById('add-service-form');
    form.action = '../../../backend/update_service.php';
    
    document.querySelector('.add-service-block h2').innerText = 'Cập nhật Dịch vụ'; 
    document.getElementById('submit-btn').innerHTML = '<i class="fas fa-check"></i> Cập nhật ngay';

    window.scrollTo({ top: 0, behavior: 'smooth' });
}
      </script>
  </body>
</html>
