<?php
session_start();

// Nếu chưa đăng nhập (không có thẻ chứng minh user_id)
if (!isset($_SESSION['user_id'])) {
    // Lưu lại cái địa chỉ trang này để sau khi đăng nhập xong nó quay lại đây luôn
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    
    // Đá người dùng sang trang đăng nhập
    echo "<script>
        alert('Vui lòng đăng nhập để sử dụng chức năng đặt lịch!');
        window.location.href = 'login.html';
    </script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lịch hẹn - Barber Shop</title>
    <link rel="stylesheet" href="../../css/css-client/style.css">
    <link rel="stylesheet" href="../../css/css-client/appointment-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                             <li><a href="index.php">Trang chủ</a></li>
                             <li><a href="about.php">Về chúng tôi</a></li>
                             <li><a href="services.php">Dịch vụ</a></li>
                         </ul>
                     </nav>
                     <div class="mobile-header-buttons">
                         <a href="appointment.php" class="btn primary-btn">Đặt lịch hẹn</a> 
                         <a href="login.html" class="btn primary-btn ">Đăng nhập</a> 
                         <a href="register.html" class="btn primary-btn">Đăng ký</a>     
                     </div>
                 </div>
            </div>

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
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="#" class="user-icon-link" style="color: #ff7f00;"> 
                        <i class="fas fa-user-circle"></i>
                        <span>Xin chào, <?php echo $_SESSION['full_name']; ?></span>
                        <i class="fas fa-chevron-down" style="font-size: 0.8rem; margin-left: 5px;"></i>
                    </a>
                    <div class="account-dropdown"> 
                        <a href="my-profile.php">Tài khoản của tôi</a>
                        <a href="history.php">Lịch sử đặt lịch</a>
                        <a href="../../../backend/logout.php" style="color: red !important;">Đăng xuất</a>
                    </div>
                <?php else: ?>
                    <a href="login.html" class="user-icon-link"> 
                        <i class="fas fa-user-circle"></i>
                        <span>Đăng nhập / Đăng ký</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="page-main">
        <div class="container">
            <h1>Đặt lịch hẹn</h1>
             <p>Vui lòng điền thông tin để đặt lịch hẹn của bạn.</p>

            <div class="appointment-form-container">
    <?php
    // KẾT NỐI DATABASE VÀO ĐÂY ĐỂ LẤY DỮ LIỆU ĐỔ RA FORM
    require_once '../../../backend/db_connect.php';
    ?>

    <form id="appointment-form" action="../../../backend/booking_process.php" method="POST"> 
        <input type="hidden" name="customer_id" value="<?php echo $_SESSION['user_id']; ?>">

        <div class="form-group">
            <label>Họ và tên khách hàng:</label>
            <input type="text" id="customer-name" value="<?php echo htmlspecialchars($_SESSION['full_name']); ?>" readonly style="background-color: #f0f0f0; cursor: not-allowed;">
        </div>

        <div class="form-group">
            <label for="service">Chọn dịch vụ <span style="color:red">*</span>:</label>
            <select id="service" name="service_id" required>
                <option value="">-- Chọn dịch vụ --</option>
                <?php
                $stmt_srv = $conn->query("SELECT service_id, service_name, price FROM services ORDER BY service_id ASC");
                while($srv = $stmt_srv->fetch()) {
                    $price_format = number_format($srv['price'], 0, ',', '.');
                    echo "<option value='{$srv['service_id']}' data-price='{$srv['price']}'>{$srv['service_name']} ({$price_format} VNĐ)</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="branch">Chọn chi nhánh <span style="color:red">*</span>:</label>
            <select id="branch" name="branch_id" required>
                <option value="">-- Chọn chi nhánh --</option>
                <?php
                $stmt_br = $conn->query("SELECT branch_id, branch_name FROM branches ORDER BY branch_id ASC");
                while($br = $stmt_br->fetch()) {
                    echo "<option value='{$br['branch_id']}'>{$br['branch_name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="barber">Chọn thợ cắt <span style="color:red">*</span>:</label>
            <select id="barber" name="stylist_id" required disabled>
                <option value="">-- Vui lòng chọn chi nhánh trước --</option>
            </select>
        </div>
        <div class="form-group">
            <label for="appointment-date">Ngày hẹn <span style="color:red">*</span>:</label>
            <input type="date" id="appointment-date" name="appointment_date" required min="<?php echo date('Y-m-d'); ?>">
        </div>

        <div class="form-group"> 
            <label for="appointment_time">Chọn khung giờ dịch vụ <span style="color:red">*</span>:</label>
            <select name="appointment_time" id="appointment_time" required disabled>
                <option value="">-- Vui lòng chọn thợ và ngày trước --</option>
            </select>
        </div>

        <div class="form-group">
            <label for="notes">Ghi chú thêm (Tùy chọn):</label>
            <textarea id="notes" name="notes" rows="3"></textarea>
        </div>

        <div class="form-group total-amount-display"> 
            <label>Tổng tiền dự kiến:</label>
            <span id="estimated-total" style="font-weight: bold; color: #ff7f00; font-size: 1.2rem;">0 VNĐ</span>
        </div>

        <button type="submit" class="btn primary-btn" style="width: 100%; height: 50px; font-weight: bold;">XÁC NHẬN ĐẶT LỊCH</button>
    </form>
</div>
            </div>
    </main>

    <footer style="background-color: #1a1a1a; color: #fff; text-align: center; padding: 20px;">
         <p>&copy; Barber Shop. All rights reserved.</p>
     </footer>

    <div class="confirmation-modal-overlay">
        <div class="confirmation-modal-content">
            <h2>Xác Nhận Lịch Hẹn</h2>
            <div class="appointment-details-summary">
                <p><strong>Họ và Tên:</strong> <span id="summary-full-name"></span></p>
                <p><strong>Salon:</strong> <span id="summary-salon"></span></p>
                <p><strong>Dịch Vụ:</strong> <span id="summary-service"></span></p>
                <p><strong>Stylist:</strong> <span id="summary-barber"></span></p>
                <p><strong>Thời Gian:</strong> <span id="summary-date-time"></span></p>
                <p><strong>Ghi Chú:</strong> <span id="summary-notes"></span></p>
                 <p class="summary-total"><strong>Tổng tiền:</strong> <span id="summary-total-amount"></span></p>
            </div>
            <div class="modal-buttons">
                <button id="confirm-appointment-btn" class="btn confirm-btn">Xác Nhận</button>
                <button id="cancel-appointment-btn" class="btn cancel-btn">Hủy Bỏ</button>
            </div>
        </div>
    </div>
    
    <script src="../../js/js-client/script.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Tính tiền
        const serviceSelect = document.getElementById('service');
        if (serviceSelect) {
            serviceSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price') || 0;
                document.getElementById('estimated-total').innerText = new Intl.NumberFormat('vi-VN').format(price) + ' VNĐ';
            });
        }

        // Khai báo các thẻ cần thao tác
        const branchSelect = document.getElementById('branch');
        const barberSelect = document.getElementById('barber');
        const dateInput = document.getElementById('appointment-date');
        const timeSelect = document.getElementById('appointment_time');
        
        // Mảng giờ chuẩn của tiệm
        const defaultHours = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30'];

        // 2. KHI CHỌN CHI NHÁNH -> TẢI THỢ
        if(branchSelect) {
            // Khi chọn Chi nhánh -> Tải danh sách thợ của chi nhánh đó
            branchSelect.addEventListener('change', function() {
                const branchId = this.value;
                barberSelect.innerHTML = '<option value="">-- Đang tải thợ... --</option>';
                barberSelect.disabled = true;

                if (branchId) {
                    // Gửi branch_id để lấy đúng thợ của chi nhánh đó
                    fetch(`../../../backend/api/get_stylists.php?branch_id=${branchId}`)
                        .then(res => res.json())
                        .then(res => {
                            barberSelect.innerHTML = '<option value="">-- Chọn Thợ --</option>';
                            if (res.status === 'success' && res.data.length > 0) {
                                res.data.forEach(stylist => {
                                    barberSelect.innerHTML += `<option value="${stylist.stylist_id}">Thợ ${stylist.full_name}</option>`;
                                });
                                barberSelect.disabled = false;
                            } else {
                                barberSelect.innerHTML = '<option value="">Chi nhánh này hiện chưa có thợ</option>';
                            }
                        });
                }
            });
        }

        // 3. KHI CHỌN THỢ / NGÀY -> KIỂM TRA GIỜ TRỐNG
        function checkAvailableSlots() {
            const date = dateInput.value;
            const stylistId = barberSelect.value;

            if (date && stylistId) {
                // Mở khóa Giờ
                timeSelect.innerHTML = '<option value="">-- Chọn giờ --</option>';
                timeSelect.disabled = false;

                // Gọi API kiểm tra lịch
                fetch(`../../../backend/api/check_slots.php?date=${date}&stylist_id=${stylistId}`)
                    .then(res => res.json())
                    .then(res => {
                        let takenSlots = [];
                        if (res.status === 'success') {
                            takenSlots = res.taken_slots;
                        }
                        
                        // Đổ giờ ra, giờ nào nằm trong takenSlots thì bôi đỏ và khóa lại
                        defaultHours.forEach(hour => {
                            let isTaken = takenSlots.includes(hour);
                            timeSelect.innerHTML += `<option value="${hour}:00" ${isTaken ? 'disabled style="color:red;"' : ''}>${hour} ${isTaken ? '(Hết chỗ)' : ''}</option>`;
                        });
                    })
                    .catch(err => console.error("Lỗi tải giờ:", err));
            } else {
                timeSelect.disabled = true;
                timeSelect.innerHTML = '<option value="">-- Vui lòng chọn thợ và ngày trước --</option>';
            }
        }

        // Gắn sự kiện thay đổi
        if(barberSelect) barberSelect.addEventListener('change', checkAvailableSlots);
        if(dateInput) dateInput.addEventListener('change', checkAvailableSlots);
    });
    </script>
</body>
</html>