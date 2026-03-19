<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý Khách hàng - Barber Shop Admin</title>
    <link rel="stylesheet" href="../../css/css-admin/style.css" />
    <link
      rel="stylesheet"
      href="../../css/css-admin/customer-management-styles.css"
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
        <h1>Quản lý Khách hàng</h1>
        <div class="customer-management-container">
          <div class="customer-search-block">
            <h2>Tìm Kiếm Khách hàng</h2>
            <form method="GET" action="" class="search-input-group" style="display: flex; gap: 10px;">
              <input
                type="text"
                id="customer-search-input"
                name="search"
                placeholder="Nhập tên, SĐT hoặc email..."
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
              />
              <button type="submit" class="btn search-btn">
                <i class="fas fa-search"></i> Tìm Kiếm
              </button>
              
              <?php if(!empty($_GET['search'])): ?>
                <a href="customer-management.php" class="btn secondary-btn" style="text-decoration: none; padding-top: 10px;">Hủy lọc</a>
              <?php endif; ?>
            </form>
          </div>

          <div class="add-customer-block">
            <h2>Thêm Khách hàng Mới</h2>
            <button class="btn primary-btn" id="show-add-customer-form-btn">
              <i class="fas fa-user-plus"></i> Thêm Khách hàng
            </button>

            <div id="add-customer-form-container" class="add-customer-form-container">
                <form id="add-customer-form">
                    <input type="hidden" id="customer-id" name="customer_id" value="">

                    <div class="form-group">
                        <label for="add-customer-name">Tên Khách hàng:</label>
                        <input type="text" id="add-customer-name" name="customer_name" placeholder="Nhập tên khách hàng" required />
                    </div>
                    <div class="form-group">
                        <label for="add-customer-phone">Số điện thoại:</label>
                        <input type="tel" id="add-customer-phone" name="customer_phone" placeholder="Nhập SĐT khách hàng" required />
                    </div>
                    <div class="form-group">
                        <label for="add-customer-email">Email (Tùy chọn):</label>
                        <input type="email" id="add-customer-email" name="customer_email" placeholder="Nhập email khách hàng" />
                    </div>
                    <button type="submit" class="btn primary-btn">
                        <i class="fas fa-save"></i> Lưu Khách hàng
                    </button>
                    <button type="button" class="btn secondary-btn" id="cancel-add-customer-form-btn">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                </form>
            </div>
          </div>

          <div class="customer-list">
            <h2>Danh sách Khách hàng</h2>
            <table>
              <thead>
                <tr>
                  <th>Mã Khách hàng</th>
                  <th>Tên Khách hàng</th>
                  <th>Số điện thoại</th>
                  <th>Email</th>
                  <th>Ngày đăng ký</th>
                  <th>Hành động</th>
                </tr>
              </thead>
              <tbody>
                <?php
                require_once '../../../backend/db_connect.php';
                
                $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                $sql = "SELECT * FROM users WHERE role = 'customer'";
                $params = [];

                if (!empty($search)) {
                    $sql .= " AND (full_name LIKE ? OR phone LIKE ? OR email LIKE ?)";
                    $searchTerm = "%$search%";
                    $params = [$searchTerm, $searchTerm, $searchTerm];
                }

                $sql .= " ORDER BY user_id DESC";
                $stmt = $conn->prepare($sql);
                $stmt->execute($params);
                $customers = $stmt->fetchAll();

                if (count($customers) > 0):
                    foreach ($customers as $c):
                        $regDate = isset($c['created_at']) ? date('d/m/Y', strtotime($c['created_at'])) : '20/03/2026';
                ?>
                    <tr>
                        <td>KH<?php echo str_pad($c['user_id'], 3, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo htmlspecialchars($c['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($c['phone'] ?? 'Chưa cập nhật'); ?></td>
                        <td><?php echo htmlspecialchars($c['email']); ?></td>
                        <td><?php echo $regDate; ?></td> 
                        <td>
                            <button class="btn edit-btn" 
                                onclick="editCustomer(this)"
                                data-id="<?php echo $c['user_id']; ?>"
                                data-name="<?php echo htmlspecialchars($c['full_name']); ?>"
                                data-phone="<?php echo htmlspecialchars($c['phone'] ?? ''); ?>"
                                data-email="<?php echo htmlspecialchars($c['email'] ?? ''); ?>"
                                style="background-color: #FFC107; color: #000;">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                            <button class="btn delete-btn" onclick="deleteCustomer(<?php echo $c['user_id']; ?>)">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </td>
                    </tr>
                <?php 
                    endforeach; 
                else: 
                    echo "<tr><td colspan='6' style='text-align:center;'>Không tìm thấy khách hàng nào.</td></tr>";
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
      <p>Barber Shop. All rights reserved.</p>
    </footer>

    <script src="../../js/js-client/script.js"></script>
    <script src="../../js/js-admin/customer-management-script.js"></script>
    
    <script>
        // 1. KHI BẤM NÚT "THÊM KHÁCH HÀNG"
        document.getElementById('show-add-customer-form-btn').addEventListener('click', function() {
            const formContainer = document.getElementById('add-customer-form-container');
            const form = document.getElementById('add-customer-form');
            
            formContainer.style.display = 'block'; 
            formContainer.classList.add('visible');
            this.style.display = 'none';

            form.reset();
            document.getElementById('customer-id').value = '';
            document.querySelector('.add-customer-block h2').innerText = 'Thêm Khách hàng Mới';
            form.querySelector('button[type="submit"]').innerHTML = '<i class="fas fa-save"></i> Lưu Khách hàng';
        });

        // 2. KHI BẤM NÚT "HỦY" (Đóng form)
        document.getElementById('cancel-add-customer-form-btn').addEventListener('click', function() {
            const formContainer = document.getElementById('add-customer-form-container');
            formContainer.style.display = 'none';
            formContainer.classList.remove('visible');
            document.getElementById('show-add-customer-form-btn').style.display = 'inline-block';
        });

        // 3. KHI BẤM NÚT "SỬA" (Bơm dữ liệu lên form)
        function editCustomer(button) {
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const phone = button.getAttribute('data-phone');
            const email = button.getAttribute('data-email');

            document.getElementById('customer-id').value = id;
            document.getElementById('add-customer-name').value = name;
            document.getElementById('add-customer-phone').value = phone;
            document.getElementById('add-customer-email').value = email;

            const form = document.getElementById('add-customer-form');
            document.querySelector('.add-customer-block h2').innerText = 'Cập nhật Khách hàng';
            form.querySelector('button[type="submit"]').innerHTML = '<i class="fas fa-check"></i> Cập nhật ngay';

            const formContainer = document.getElementById('add-customer-form-container');
            formContainer.style.display = 'block';
            formContainer.classList.add('visible');
            document.getElementById('show-add-customer-form-btn').style.display = 'none';

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // 4. BẮT SỰ KIỆN SUBMIT FORM BẰNG API
        document.getElementById('add-customer-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const id = document.getElementById('customer-id').value;
            const isUpdate = (id !== ''); 
            const apiUrl = isUpdate ? '../../../backend/update_customer.php' : '../../../backend/add_customer.php';

            const data = {
                customer_id: id,
                customer_name: document.getElementById('add-customer-name').value,
                customer_phone: document.getElementById('add-customer-phone').value,
                customer_email: document.getElementById('add-customer-email').value
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

        // 5. KHI BẤM NÚT "XÓA" BẰNG API
        function deleteCustomer(id) {
            if (confirm('Cảnh báo: Bạn có chắc chắn muốn xóa khách hàng này?')) {
                fetch(`../../../backend/delete_customer.php?id=${id}`)
                .then(response => response.json())
                .then(result => {
                    alert(result.message);
                    if (result.status === 'success') location.reload();
                })
                .catch(error => console.error('Lỗi API:', error));
            }
        }
    </script>
  </body>
</html>