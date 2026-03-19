<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Của Tôi - Barber Shop</title>
    <link rel="stylesheet" href="../../css/css-client/style.css">
    <link rel="stylesheet" href="../../css/css-client/my-profile-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        // Kiểm tra đăng nhập
        if (!localStorage.getItem('token') || !localStorage.getItem('user_id')) {
            alert('Vui lòng đăng nhập để xem thông tin!');
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
            <div class="header-buttons desktop-buttons" id="auth-menu-container">
                </div>
        </div>
    </header>

    <main class="page-main">
        <div class="container">
            <div class="profile-card">
                <h2>Thông Tin Cá Nhân Của Tôi</h2> 
                <form id="profile-form">
                    <div class="form-group">
                        <label>Tên Khách hàng:</label>
                        <input type="text" id="profile-name" readonly style="background-color: #f0f0f0;">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại:</label>
                        <input type="tel" id="profile-phone" readonly style="background-color: #f0f0f0;">
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" id="profile-email" readonly style="background-color: #f0f0f0;"> 
                    </div>
                    <div class="form-group">
                        <label>Ngày đăng ký:</label>
                        <input type="text" id="profile-date" readonly style="background-color: #f0f0f0;">
                    </div>
                </form>
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

            // Hiển thị Menu User
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

            // GỌI API LẤY THÔNG TIN HỒ SƠ
            fetch(`../../../backend/api/get_profile.php?user_id=${userId}`)
            .then(res => res.json())
            .then(result => {
                if(result.status === 'success') {
                    const user = result.data;
                    document.getElementById('profile-name').value = user.full_name;
                    document.getElementById('profile-phone').value = user.phone;
                    document.getElementById('profile-email').value = user.email || 'Chưa cập nhật';
                    document.getElementById('profile-date').value = user.created_at;
                }
            })
            .catch(err => console.error("Lỗi API Profile:", err));
        });

        function logoutUser() {
            localStorage.clear();
            window.location.href = 'index.php';
        }
    </script>
</body>
</html>