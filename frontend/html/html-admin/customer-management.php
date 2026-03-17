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
            <div class="search-input-group">
              <input
                type="text"
                id="customer-search-input"
                name="customer_search"
                placeholder="Nhập tên, SĐT hoặc email khách hàng"
              />
              <button
                type="button"
                id="customer-search-btn"
                class="btn search-btn"
              >
                <i class="fas fa-search"></i> Tìm Kiếm
              </button>
            </div>
          </div>

          <div class="add-customer-block">
            <h2>Thêm Khách hàng Mới</h2>
            <button class="btn primary-btn" id="show-add-customer-form-btn">
              <i class="fas fa-user-plus"></i> Thêm Khách hàng
            </button>

            <div
              id="add-customer-form-container"
              class="add-customer-form-container"
            >
              <form id="add-customer-form">
                <div class="form-group">
                  <label for="add-customer-name">Tên Khách hàng:</label>
                  <input
                    type="text"
                    id="add-customer-name"
                    name="customer_name"
                    placeholder="Nhập tên khách hàng"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="add-customer-phone">Số điện thoại:</label>
                  <input
                    type="tel"
                    id="add-customer-phone"
                    name="customer_phone"
                    placeholder="Nhập SĐT khách hàng"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="add-customer-email">Email (Tùy chọn):</label>
                  <input
                    type="email"
                    id="add-customer-email"
                    name="customer_email"
                    placeholder="Nhập email khách hàng"
                  />
                </div>
                <button type="submit" class="btn primary-btn">
                  <i class="fas fa-save"></i> Lưu Khách hàng
                </button>
                <button
                  type="button"
                  class="btn secondary-btn"
                  id="cancel-add-customer-form-btn"
                >
                  <i class="fas fa-times"></i> Hủy
                </button>
              </form>
            </div>
          </div>

          <div
            id="edit-customer-form-container"
            class="edit-customer-form-container"
          >
            <h2>Chỉnh Sửa Thông Tin Khách Hàng</h2>
            <form id="edit-customer-form">
              <input type="hidden" id="edit-customer-id" name="customer_id" />

              <div class="form-group">
                <label for="edit-customer-name">Tên Khách hàng:</label>
                <input
                  type="text"
                  id="edit-customer-name"
                  name="customer_name"
                  required
                />
              </div>
              <div class="form-group">
                <label for="edit-customer-phone">Số điện thoại:</label>
                <input
                  type="tel"
                  id="edit-customer-phone"
                  name="customer_phone"
                  required
                />
              </div>
              <div class="form-group">
                <label for="edit-customer-email">Email (Tùy chọn):</label>
                <input
                  type="email"
                  id="edit-customer-email"
                  name="customer_email"
                />
              </div>
              <div class="form-group">
                <label for="edit-customer-reg-date">Ngày đăng ký:</label>
                <input
                  type="text"
                  id="edit-customer-reg-date"
                  name="customer_reg_date"
                  readonly
                />
              </div>

              <button type="submit" class="btn primary-btn">
                <i class="fas fa-save"></i> Cập Nhật
              </button>
              <button
                type="button"
                class="btn secondary-btn"
                id="cancel-edit-customer-form-btn"
              >
                <i class="fas fa-times"></i> Hủy
              </button>
            </form>
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
                <tr data-id="KH001">
                  <td>KH001</td>
                  <td>Nguyễn Văn A</td>
                  <td>0912345678</td>
                  <td>nguyen.a@example.com</td>
                  <td>01/01/2023</td>
                  <td>
                    <button
                      class="btn secondary-btn view-details-btn"
                      data-id="KH001"
                    >
                      <i class="fas fa-info-circle"></i> Chi tiết
                    </button>
                    <button class="btn edit-btn" data-id="KH001">
                      <i class="fas fa-edit"></i> Sửa
                    </button>
                    <button class="btn delete-btn" data-id="KH001">
                      <i class="fas fa-trash"></i> Xóa
                    </button>
                  </td>
                </tr>
                <tr data-id="KH002">
                  <td>KH002</td>
                  <td>Trần Thị B</td>
                  <td>0987654321</td>
                  <td>tran.b@example.com</td>
                  <td>15/03/2023</td>
                  <td>
                    <button
                      class="btn secondary-btn view-details-btn"
                      data-id="KH002"
                    >
                      <i class="fas fa-info-circle"></i> Chi tiết
                    </button>
                    <button class="btn edit-btn" data-id="KH002">
                      <i class="fas fa-edit"></i> Sửa
                    </button>
                    <button class="btn delete-btn" data-id="KH002">
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
    <script src="../../js/js-admin/customer-management-script.js"></script>
  </body>
</html>
