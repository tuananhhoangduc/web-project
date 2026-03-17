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
                             <li><a href="about.html">Về chúng tôi</a></li>
                             <li><a href="services.html">Dịch vụ</a></li>
                              
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
                    <li><a href="about.html">Về chúng tôi</a></li> 
                    <li><a href="services.html">Dịch vụ</a></li> 
                      
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
    </header>

    <main class="page-main">
        <div class="container">
            <h1>Đặt lịch hẹn</h1>
             <p>Vui lòng điền thông tin để đặt lịch hẹn của bạn.</p>

            <div class="appointment-form-container">
    <form id="appointment-form" action="../../../backend/booking_process.php" method="POST"> 
        <input type="hidden" name="customer_id" value="<?php echo $_SESSION['user_id']; ?>">

        <div class="form-group">
            <label>Họ và tên khách hàng:</label>
            <input type="text" value="<?php echo $_SESSION['full_name']; ?>" readonly style="background-color: #f0f0f0; cursor: not-allowed;">
        </div>

        <div class="form-group">
            <label for="appointment-date">Ngày hẹn <span style="color:red">*</span>:</label>
            <input type="date" id="appointment-date" name="appointment_date" required min="<?php echo date('Y-m-d'); ?>">
        </div>

        <div class="form-group"> 
            <label for="appointment_time">Chọn khung giờ dịch vụ <span style="color:red">*</span>:</label>
            <select name="appointment_time" id="appointment_time" required>
                <option value="">-- Chọn giờ --</option>
                <?php 
                    $hours = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30'];
                    foreach($hours as $h) { echo "<option value='$h:00'>$h</option>"; }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="service">Chọn dịch vụ <span style="color:red">*</span>:</label>
            <select id="service" name="service_id" required>
                <option value="">-- Chọn dịch vụ --</option>
                <option value="1" data-price="150000">Cắt tóc (150.000 VNĐ)</option>
                <option value="2" data-price="100000">Cạo râu (100.000 VNĐ)</option> 
                <option value="3" data-price="200000">Chăm sóc da mặt (200.000 VNĐ)</option>
                <option value="4" data-price="500000">Nhuộm tóc (500.000 VNĐ)</option> 
            </select>
        </div>

        <div class="form-group">
            <label for="branch">Chọn chi nhánh <span style="color:red">*</span>:</label>
            <select id="branch" name="branch_id" required>
                <option value="">-- Chọn chi nhánh --</option>
                <option value="1">Chi nhánh Xuân Thủy, Quận Cầu Giấy</option>
                <option value="2">Chi nhánh Chùa Bộc, Quận Đống Đa</option> 
                <option value="3">Chi nhánh Phố Huế, Quận Hai Bà Trưng</option>
                <option value="4">Chi nhánh Trần Phú, Quận Hà Đông</option> 
                <option value="5">Chi nhánh Nguyễn Trãi, Quận Thanh Xuân</option> 
                <option value="6">Chi nhánh Lê Đức Thọ, Quận Nam Từ Liêm</option>
            </select>
        </div>

        <div class="form-group">
            <label for="barber">Chọn thợ (Tùy chọn):</label>
            <select id="barber" name="stylist_id">
                <option value="">-- Để Salon tự sắp xếp --</option>
                <option value="1">Thợ Lê Hiếu</option>
                <option value="2">Thợ Khoa Đăng</option>
                <option value="3">Thợ Ngọc Đăng</option>
                <option value="4">Thợ Tuấn Anh</option>
                <option value="5">Thợ An Lê</option>
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
                <p><strong>Số Điện Thoại:</strong> <span id="summary-phone"></span></p>
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
    // 1. Tự động tính tiền khi chọn dịch vụ
    const serviceSelect = document.getElementById('service');
    if (serviceSelect) {
        serviceSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            document.getElementById('estimated-total').innerText = new Intl.NumberFormat('vi-VN').format(price) + ' VNĐ';
        });
    }

    // 2. Tự động kiểm tra và khóa các khung giờ đã có người đặt
    const dateInput = document.getElementById('appointment-date');
    if (dateInput) {
        dateInput.addEventListener('change', function() {
            const date = this.value;
            const timeSelect = document.getElementById('appointment_time');
            
            // Reset danh sách giờ
            Array.from(timeSelect.options).forEach(opt => {
                if(opt.value !== "") {
                    opt.disabled = false;
                    opt.text = opt.value.substring(0, 5); 
                }
            });

            // Gọi AJAX kiểm tra giờ bận (Đường dẫn lùi 3 cấp giống action của form)
            fetch(`../../../backend/check_slots.php?date=${date}`)
                .then(res => res.json())
                .then(takenSlots => {
                    Array.from(timeSelect.options).forEach(opt => {
                        if (takenSlots.includes(opt.value)) {
                            opt.disabled = true;
                            opt.text += " (Hết chỗ)";
                        }
                    });
                })
                .catch(err => console.error("Lỗi kiểm tra lịch:", err));
        });
    }
    </script>
<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
</html>

