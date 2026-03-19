<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Sử Đặt Hẹn - Barber Shop</title>
    <link rel="stylesheet" href="../../css/css-client/style.css">
    <link rel="stylesheet" href="../../css/css-client/history-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        if (!localStorage.getItem('token') || !localStorage.getItem('user_id')) {
            alert('Vui lòng đăng nhập để xem lịch sử!');
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
            <h1>Lịch Sử Đặt Hẹn</h1>
            <p class="customer-history-info">Danh sách các lịch hẹn của bạn:</p>

            <div class="appointment-history-list" id="history-list-container">
                <p style="text-align: center;">Đang tải dữ liệu...</p>
            </div>        
        </div>
    </main>

    <footer style="background-color: #1a1a1a; color: #fff; text-align: center; padding: 20px;">
        <p>&copy; Barber Shop. All rights reserved.</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fullName = localStorage.getItem('full_name');
            const userId = localStorage.getItem('user_id');

            // Render Menu User
            document.getElementById('auth-menu-container').innerHTML = `
                <div class="user-account">
                    <a href="#" class="user-icon-link" style="color: #ff7f00;"> 
                        <i class="fas fa-user-circle"></i> <span>Xin chào, ${fullName}</span>
                    </a>
                    <div class="account-dropdown"> 
                        <a href="my-profile.php">Tài khoản của tôi</a>
                        <a href="history.php">Lịch sử đặt lịch</a>
                        <a href="#" onclick="logoutUser()" style="color: red !important;">Đăng xuất</a>
                    </div>
                </div>`;

            // GỌI API LẤY LỊCH SỬ
            fetch(`../../../backend/api/get_history.php?user_id=${userId}`)
            .then(res => res.json())
            .then(result => {
                const container = document.getElementById('history-list-container');
                container.innerHTML = ''; // Xóa chữ Đang tải...

                if(result.status === 'success' && result.data.length > 0) {
                    result.data.forEach(app => {
                        let statusText = 'Chờ xác nhận', statusColor = '#f39c12';
                        if (app.status === 'confirmed') { statusText = 'Đã xác nhận'; statusColor = '#27ae60'; }
                        if (app.status === 'completed') { statusText = 'Đã hoàn thành'; statusColor = '#2980b9'; }
                        if (app.status === 'cancelled') { statusText = 'Đã hủy'; statusColor = '#c0392b'; }

                        const formattedPrice = new Intl.NumberFormat('vi-VN').format(app.total_price);
                        
                        let html = `
                            <div class="appointment-history-item" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 8px;">
                                <div class="appointment-details-card">
                                    <p><strong>Salon:</strong> ${app.branch_name}</p>
                                    <p><strong>Dịch Vụ:</strong> ${app.service_name} (${formattedPrice} VNĐ)</p>
                                    <p><strong>Thợ Cắt:</strong> ${app.stylist_name || 'Chưa sắp xếp'}</p>
                                    <p><strong>Thời Gian:</strong> ${app.appointment_date}, ${app.appointment_time.substring(0,5)}</p>
                                    <p><strong>Ghi Chú:</strong> ${app.notes || 'Không có ghi chú'}</p>
                                    <p class="appointment-status-card" style="color: ${statusColor}; font-weight: bold;">
                                        Trạng Thái: ${statusText}
                                    </p>
                                </div>`;
                        
                        // Nếu đang chờ duyệt thì hiện nút Hủy (gọi bằng API Hủy bạn đã làm)
                        if (app.status === 'pending') {
                            html += `
                                <div style="margin-top: 10px;">
                                    <button class="btn" style="background-color: #e74c3c; color: white; padding: 8px 15px; border:none; cursor:pointer; border-radius: 4px;"
                                            onclick="cancelAppointment(${app.appointment_id})">Hủy Lịch</button>
                                </div>`;
                        }

                        html += `</div>`;
                        container.innerHTML += html;
                    });
                } else {
                    container.innerHTML = "<p style='text-align: center; color: #777;'>Bạn chưa có lịch hẹn nào.</p>";
                }
            })
            .catch(err => console.error("Lỗi API History:", err));
        });

        // Hàm gọi API Hủy Lịch
        function cancelAppointment(appId) {
            if(confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?')) {
                const userId = localStorage.getItem('user_id');
                fetch(`../../../backend/cancel_appointment_client.php?id=${appId}&customer_id=${userId}`)
                .then(res => res.json())
                .then(result => {
                    alert(result.message);
                    if(result.status === 'success') location.reload();
                })
                .catch(err => console.error("Lỗi hủy lịch:", err));
            }
        }

        function logoutUser() {
            localStorage.clear();
            window.location.href = 'index.php';
        }
    </script>
</body>
</html>