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
              <form id="add-service-form">
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
                  <th>Hành động</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>DV001</td>
                  <td>Cắt tóc Nam</td>
                  <td>150,000 VNĐ</td>
                  <td>30 phút</td>
                  <td>
                    <button class="btn edit-btn">
                      <i class="fas fa-edit"></i> Sửa
                    </button>
                    <button class="btn delete-btn">
                      <i class="fas fa-trash"></i> Xóa
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>DV002</td>
                  <td>Cạo râu</td>
                  <td>50,000 VNĐ</td>
                  <td>15 phút</td>
                  <td>
                    <button class="btn edit-btn">
                      <i class="fas fa-edit"></i> Sửa
                    </button>
                    <button class="btn delete-btn">
                      <i class="fas fa-trash"></i> Xóa
                    </button>
                  </td>
                </tr>
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
  </body>
</html>
