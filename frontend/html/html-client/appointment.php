<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lịch hẹn - Barber Shop</title>
    <link rel="stylesheet" href="../../css/css-client/style.css">
    <link rel="stylesheet" href="../../css/css-client/appointment-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script>
        // Chặn người dùng nếu chưa đăng nhập
        if (!localStorage.getItem('user_id')) {
            alert('Vui lòng đăng nhập để sử dụng chức năng đặt lịch!');
            window.location.href = 'login.html';
        }
    </script>
</head>
<body> 

    <header class="site-header">
        <div class="container header-container">
            <div class="logo"><img src="../../image/logo.png" alt="Barber Shop Logo"></div>
            <nav class="desktop-nav">
                <ul>
                    <li><a href="index.php">Trang chủ</a></li>
                    <li><a href="about.php">Về chúng tôi</a></li>
                    <li><a href="services.php">Dịch vụ</a></li>
                </ul>
            </nav>
            
            <div class="header-buttons desktop-buttons" id="auth-menu-container"></div>
        </div>
    </header>

    <main class="page-main">
        <div class="container">
            <h1>Đặt lịch hẹn</h1>
            <div class="appointment-form-container">
                <?php require_once '../../../backend/db_connect.php'; ?>
                
                <form id="appointment-form"> 
                    <div class="form-group">
                        <label>Họ và tên khách hàng:</label>
                        <input type="text" id="customer-name" readonly style="background-color: #f0f0f0; font-weight: bold;">
                    </div>

                    <div class="form-group">
                        <label for="service">Chọn dịch vụ <span style="color:red">*</span>:</label>
                        <select id="service" required>
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
                        <select id="branch" required>
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
                        <select id="barber" required disabled>
                            <option value="">-- Vui lòng chọn chi nhánh trước --</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="appointment-date">Ngày hẹn <span style="color:red">*</span>:</label>
                        <input type="date" id="appointment-date" required min="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <div class="form-group"> 
                        <label for="appointment_time">Chọn khung giờ dịch vụ <span style="color:red">*</span>:</label>
                        <select id="appointment_time" required disabled>
                            <option value="">-- Vui lòng chọn thợ và ngày trước --</option>
                        </select>
                    </div>

                    <div class="form-group"><label>Ghi chú thêm:</label><textarea id="notes" rows="3"></textarea></div>

                    <div class="form-group total-amount-display"> 
                        <label>Tổng tiền dự kiến:</label>
                        <span id="estimated-total" style="font-weight: bold; color: #ff7f00; font-size: 1.2rem;">0 VNĐ</span>
                    </div>

                    <button type="submit" class="btn primary-btn" style="width: 100%; height: 50px; font-weight: bold;">XÁC NHẬN ĐẶT LỊCH</button>
                </form>
            </div>
        </div>
    </main>

    <footer style="background-color: #1a1a1a; color: #fff; text-align: center; padding: 20px;"><p>&copy; Barber Shop. All rights reserved.</p></footer>
    
    <script src="../../js/js-client/script.js"></script>
    
    <script src="../../js/js-client/auth-menu.js"></script> 
    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Tự động điền tên khách hàng
        const customerInput = document.getElementById('customer-name');
        if (customerInput) customerInput.value = localStorage.getItem('full_name') || 'Khách hàng';

        // Các thành phần form
        const serviceSelect = document.getElementById('service');
        const branchSelect = document.getElementById('branch');
        const barberSelect = document.getElementById('barber');
        const dateInput = document.getElementById('appointment-date');
        const timeSelect = document.getElementById('appointment_time');
        
        // 1. Tính tiền dịch vụ
        if (serviceSelect) {
            serviceSelect.addEventListener('change', function() {
                const price = this.options[this.selectedIndex].getAttribute('data-price') || 0;
                document.getElementById('estimated-total').innerText = new Intl.NumberFormat('vi-VN').format(price) + ' VNĐ';
            });
        }

        // 2. Tải Thợ theo Chi nhánh
        if(branchSelect) {
            branchSelect.addEventListener('change', function() {
                barberSelect.innerHTML = '<option value="">-- Đang tải thợ... --</option>';
                barberSelect.disabled = true;
                if (this.value) {
                    fetch(`../../../backend/api/get_stylists.php?branch_id=${this.value}`)
                        .then(res => res.json())
                        .then(res => {
                            barberSelect.innerHTML = '<option value="">-- Chọn Thợ --</option>';
                            if (res.status === 'success' && res.data.length > 0) {
                                res.data.forEach(s => barberSelect.innerHTML += `<option value="${s.stylist_id}">Thợ ${s.full_name}</option>`);
                                barberSelect.disabled = false;
                            } else {
                                barberSelect.innerHTML = '<option value="">Chi nhánh này chưa có thợ</option>';
                            }
                        }).catch(() => barberSelect.innerHTML = '<option value="">-- Lỗi tải dữ liệu --</option>');
                }
            });
        }

        // 3. Tải Giờ trống
        const defaultHours = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30'];
        
        const checkAvailableSlots = () => {
            if (dateInput.value && barberSelect.value) {
                timeSelect.innerHTML = '<option value="">-- Đang kiểm tra giờ... --</option>';
                timeSelect.disabled = false;
                fetch(`../../../backend/api/check_slots.php?date=${dateInput.value}&stylist_id=${barberSelect.value}`)
                    .then(res => res.json())
                    .then(res => {
                        timeSelect.innerHTML = '<option value="">-- Chọn giờ --</option>';
                        let takenSlots = res.status === 'success' ? res.taken_slots : [];
                        defaultHours.forEach(hour => {
                            let isTaken = takenSlots.includes(hour);
                            timeSelect.innerHTML += `<option value="${hour}:00" ${isTaken ? 'disabled style="color:red;"' : ''}>${hour} ${isTaken ? '(Hết chỗ)' : ''}</option>`;
                        });
                    }).catch(() => timeSelect.innerHTML = '<option value="">-- Lỗi tải dữ liệu --</option>');
            } else {
                timeSelect.disabled = true;
                timeSelect.innerHTML = '<option value="">-- Vui lòng chọn thợ và ngày trước --</option>';
            }
        };

        if(barberSelect) barberSelect.addEventListener('change', checkAvailableSlots);
        if(dateInput) dateInput.addEventListener('change', checkAvailableSlots);

        // 4. Submit Đặt Lịch
        document.getElementById('appointment-form').addEventListener('submit', function(e) {
            e.preventDefault(); 
            const data = {
                customer_id: localStorage.getItem('user_id'),
                branch_id: branchSelect.value,
                service_id: serviceSelect.value,
                stylist_id: barberSelect.value,
                appointment_date: dateInput.value,
                appointment_time: timeSelect.value,
                notes: document.getElementById('notes').value
            };

            fetch('../../../backend/booking_process.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(result => {
                alert(result.message);
                if (result.status === 'success') window.location.href = 'history.php';
            })
            .catch(() => alert("Đã xảy ra lỗi kết nối Server!"));
        });
    });
    </script>
</body>
</html>