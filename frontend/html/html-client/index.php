<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Barber Shop</title>
    <link rel="stylesheet" href="../../css/css-client/style.css">
    <link rel="stylesheet" href="../../css/css-client/index-styles.css">
    <link rel="stylesheet" href="../../css/css-client/about-style.css">
    <link rel="stylesheet" href="../../css/css-client/services-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head> 
<body>
    <header class="site-header">
        <div class="container header-container">
            <div class="logo"><img src="../../image/logo.png" alt="Barber Shop Logo"></div>
            <div class="hamburger-icon"><i class="fas fa-bars"></i></div>
            <div class="mobile-menu-overlay">
                 <div class="mobile-menu-content">
                     <div class="close-icon"><i class="fas fa-times"></i></div>
                     <nav class="mobile-nav">
                         <ul>
                             <li><a href="index.php">Trang chủ</a></li>
                             <li><a href="about.php">Về chúng tôi</a></li>
                             <li><a href="services.php">Dịch vụ</a></li>
                         </ul>
                     </nav>
                     <div class="mobile-header-buttons" id="mobile-auth-container"></div>
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
            
            <div class="header-buttons desktop-buttons" id="auth-menu-container"></div>
        </div>
    </header>

    <main>
        <section class="hero-section">
            <div class="hero-background">
                <img src="../../image/hero-bg-1.jpg" alt="" class="slide active">
                <img src="../../image/hero-bg-2.jpg" alt="" class="slide">
                <img src="../../image/hero-bg-3.jpg" alt="" class="slide">
            </div>
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1>Không Chỉ Là Cắt Tóc, Đó Là Một <br> Trải Nghiệm</h1>
                <p>Tiệm cắt tóc của chúng tôi là không gian được tạo ra dành riêng cho những người đàn ông đề cao chất lượng.</p>
                <div class="hero-cta"><a href="appointment.php" class="btn primary-btn">Đặt lịch ngay</a></div>
            </div>
            <div class="slider-indicators"><span class="active"></span><span></span><span></span></div>
        </section>
    </main>

    <main class="page-main">
      <div class="container">
        <h1>Về chúng tôi</h1>
        <section class="about-section">
          <div class="about-content">
            <h2>Câu chuyện của chúng tôi</h2>
            <p>Từ năm 2000, Barber Shop đã tự hào mang đến những trải nghiệm cắt tóc và chăm sóc phái mạnh đẳng cấp...</p>
            <p>Đội ngũ thợ cắt tóc của chúng tôi là những người thợ lành nghề, giàu kinh nghiệm...</p>
          </div>
          <div class="about-image"><img src="../../image/about-1.jpg" alt="Hình ảnh về Barber Shop" /></div>
        </section>
      </div>
    </main>

    <main class="services-main1">
      <div class="container">
        <h1>Dịch vụ của chúng tôi</h1>
        <p class="subtitle">Những trải nghiệm đẳng cấp dành riêng cho quý ông</p>
        <section class="services-grid">
          <div class="service-card">
            <div class="service-image"><img src="../../image/cat-toc.jpg" alt="Cắt tóc nam" /></div>
            <div class="service-content"><h3>Cắt tóc nam</h3><p class="price">150.000đ - 300.000đ</p><p>Cắt tỉa theo phong cách hiện đại hoặc cổ điển...</p></div>
          </div>
          <div class="service-card">
            <div class="service-image"><img src="../../image/cao-rau.jpg" alt="Cạo râu" /></div>
            <div class="service-content"><h3>Cạo râu truyền thống</h3><p class="price">200.000đ</p><p>Sử dụng dao cạo chuyên nghiệp kết hợp dưỡng ẩm da...</p></div>
          </div>
          <div class="service-card">
            <div class="service-image"><img src="../../image/uon-toc.jpg" alt="Uốn tóc" /></div>
            <div class="service-content"><h3>Uốn tóc nam</h3><p class="price">400.000đ - 600.000đ</p><p>Uốn phồng, uốn lạnh hoặc uốn Hàn Quốc...</p></div>
          </div>
        </section>
        <div class="subtitle"><a href="services.php" class="btn primary-btn">Xem thêm </a></div>
      </div>
    </main>

    <footer style="background-color: #1a1a1a; color: #fff; text-align: center; padding: 20px;"><p>&copy; Barber Shop. All rights reserved.</p></footer>

    <script src="../../js/js-client/script.js"></script> 
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fullName = localStorage.getItem('full_name');
            const authContainer = document.getElementById('auth-menu-container');
            const mobileAuth = document.getElementById('mobile-auth-container');

            if (fullName) {
                // ĐÃ ĐĂNG NHẬP (Token exists)
                authContainer.innerHTML = `
                    <div class="user-account" style="position: relative;">
                        <a href="#" class="user-icon-link" style="color: #ff7f00;"> 
                            <i class="fas fa-user-circle"></i> <span>Xin chào, ${fullName}</span>
                        </a>
                        <div class="account-dropdown" style="display: none;"> 
                            <a href="my-profile.php">Tài khoản của tôi</a>
                            <a href="history.php">Lịch sử đặt lịch</a>
                            <a href="#" onclick="logoutUser()" style="color: red !important;">Đăng xuất</a>
                        </div>
                    </div>`;
                mobileAuth.innerHTML = `<a href="my-profile.php" class="btn primary-btn">Tài khoản</a><a href="#" onclick="logoutUser()" class="btn primary-btn" style="background:red;">Đăng xuất</a>`;
            } else {
                // CHƯA ĐĂNG NHẬP
                authContainer.innerHTML = `<a href="login.html" class="user-icon-link"><i class="fas fa-user-circle"></i> <span>Đăng nhập / Đăng ký</span></a>`;
                mobileAuth.innerHTML = `<a href="login.html" class="btn primary-btn">Đăng nhập</a><a href="register.html" class="btn primary-btn">Đăng ký</a>`;
            }
        });

        function logoutUser() {
            localStorage.clear();
            window.location.reload();
        }
    </script>
</body>
</html>