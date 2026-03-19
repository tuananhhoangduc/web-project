<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8"><title>Đặt lịch hẹn - Barber Shop</title>
    <link rel="stylesheet" href="../../css/css-client/style.css">
    <link rel="stylesheet" href="../../css/css-client/appointment-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>if (!localStorage.getItem('token')) { alert('Vui lòng đăng nhập!'); window.location.href = 'login.html'; }</script>
</head>
<body> 
    <header class="site-header">
        <div class="container header-container">
            <div class="logo"><img src="../../image/logo.png" alt="Barber Shop Logo"></div>
            <nav class="desktop-nav"><ul><li><a href="index.php">Trang chủ</a></li><li><a href="about.php">Về chúng tôi</a></li><li><a href="services.php">Dịch vụ</a></li></ul></nav>
            <div class="header-buttons desktop-buttons" id="auth-menu-container"></div>
        </div>
    </header>
    <main class="page-main"><div class="container"><h1>Đặt lịch hẹn</h1>
        <div class="appointment-form-container">
            <form id="appointment-form">
                <div class="form-group"><label>Khách hàng:</label><input type="text" id="cust-name" readonly style="background:#f0f0f0;"></div>
                <div class="form-group"><label>Dịch vụ:</label><select id="service" required><option value="">-- Chọn dịch vụ --</option>
                    <?php require_once '../../../backend/db_connect.php'; 
                    $srvs = $conn->query("SELECT * FROM services")->fetchAll();
                    foreach($srvs as $s) echo "<option value='{$s['service_id']}' data-price='{$s['price']}'>{$s['service_name']}</option>"; ?>
                </select></div>
                <div class="form-group"><label>Chi nhánh:</label><select id="branch" required><option value="">-- Chọn chi nhánh --</option>
                    <?php $brs = $conn->query("SELECT * FROM branches")->fetchAll();
                    foreach($brs as $b) echo "<option value='{$b['branch_id']}'>{$b['branch_name']}</option>"; ?>
                </select></div>
                <div class="form-group"><label>Thợ cắt:</label><select id="barber" required disabled><option value="">-- Chọn chi nhánh trước --</option></select></div>
                <div class="form-group"><label>Ngày hẹn:</label><input type="date" id="app-date" required min="<?=date('Y-m-d')?>"></div>
                <div class="form-group"><label>Giờ hẹn:</label><select id="app-time" required disabled><option value="">-- Chọn thợ và ngày trước --</option></select></div>
                <div class="form-group"><label>Ghi chú:</label><textarea id="notes" rows="3"></textarea></div>
                <div class="form-group"><label>Tổng tiền dự kiến:</label><span id="estimated-total" style="color:#ff7f00; font-weight:bold;">0 VNĐ</span></div>
                <button type="submit" class="btn primary-btn" style="width:100%;">XÁC NHẬN ĐẶT LỊCH</button>
            </form>
        </div>
    </div></main>
    <script src="../../js/js-client/script.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('cust-name').value = localStorage.getItem('full_name');
        document.getElementById('auth-menu-container').innerHTML = `<div class="user-account" style="position:relative;"><a href="#" class="user-icon-link" style="color:#ff7f00;"><i class="fas fa-user-circle"></i> <span>Xin chào, ${localStorage.getItem('full_name')}</span></a><div class="account-dropdown" style="display:none;"><a href="my-profile.php">Hồ sơ</a><a href="history.php">Lịch sử</a><a href="#" onclick="logoutUser()">Đăng xuất</a></div></div>`;

        // Logic Load thợ và giờ (giống các phiên bản trước bạn đã có)
        const branchSelect = document.getElementById('branch');
        const barberSelect = document.getElementById('barber');
        const dateInput = document.getElementById('app-date');
        const timeSelect = document.getElementById('app-time');

        branchSelect.onchange = function() {
            fetch(`../../../backend/get_stylists.php?branch_id=${this.value}`).then(r => r.json()).then(res => {
                barberSelect.innerHTML = '<option value="">-- Chọn Thợ --</option>';
                if(res.status==='success') res.data.forEach(s => barberSelect.innerHTML += `<option value="${s.stylist_id}">${s.full_name}</option>`);
                barberSelect.disabled = false;
            });
        };

        // Gửi Form Đặt lịch
        document.getElementById('appointment-form').onsubmit = function(e) {
            e.preventDefault();
            const data = {
                customer_id: localStorage.getItem('user_id'), branch_id: branchSelect.value, service_id: document.getElementById('service').value,
                stylist_id: barberSelect.value, appointment_date: dateInput.value, appointment_time: timeSelect.value, notes: document.getElementById('notes').value
            };
            fetch('../../../backend/booking_process.php', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(data) })
            .then(r => r.json()).then(res => { alert(res.message); if(res.status==='success') window.location.href='history.php'; });
        };
        
        // Load giờ trống (check_slots) - bổ sung logic này vào nếu cần
        const checkSlots = () => {
            if(barberSelect.value && dateInput.value) {
                fetch(`../../../backend/check_slots.php?date=${dateInput.value}&stylist_id=${barberSelect.value}`).then(r=>r.json()).then(res=>{
                    timeSelect.innerHTML = '<option value="">-- Chọn giờ --</option>';
                    ['08:00','09:00','10:00','14:00','15:00'].forEach(h => {
                        let taken = res.taken_slots.includes(h);
                        timeSelect.innerHTML += `<option value="${h}:00" ${taken?'disabled style="color:red"':''}>${h} ${taken?'(Hết)':''}</option>`;
                    });
                    timeSelect.disabled = false;
                });
            }
        };
        barberSelect.onchange = checkSlots; dateInput.onchange = checkSlots;
    });
    </script>
</body>
</html>