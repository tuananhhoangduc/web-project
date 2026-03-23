<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Về chúng tôi - Barber Shop</title>
    <link rel="stylesheet" href="../../css/css-client/style.css" />
    <link rel="stylesheet" href="../../css/css-client/about-style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  </head>
  <body>
    <header class="site-header">
        <div class="container header-container">
            <div class="logo">
                <img src="../../image/logo.png" alt="Barber Shop Logo">
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
                            <span>Xin chào, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
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
        </div>
    </header>

    <main class="page-main">
      <div class="container">
        <h1>Về chúng tôi</h1>

        <section class="about-section">
          <div class="about-content">
            <h2>Câu chuyện của chúng tôi</h2>
            <p>Từ năm 2000, Barber Shop đã tự hào mang đến những trải nghiệm cắt tóc và chăm sóc phái mạnh đẳng cấp. Với hơn 20 năm kinh nghiệm trong nghề, chúng tôi không chỉ đơn thuần là một tiệm cắt tóc, mà là một không gian nơi quý ông có thể thư giãn, làm mới bản thân và tìm thấy phong cách phù hợp nhất.</p>
            <p>Đội ngũ thợ cắt tóc của chúng tôi là những người thợ lành nghề, giàu kinh nghiệm và luôn cập nhật những xu hướng tóc mới nhất. Chúng tôi tin rằng mỗi kiểu tóc là một tuyên ngôn về cá tính, và chúng tôi cam kết mang đến cho bạn diện mạo hoàn hảo, thể hiện phong thái tự tin và lịch lãm.</p>
          </div>
          <div class="about-image">
            <img src="../../image/about-1.jpg" alt="Hình ảnh về Barber Shop" />
          </div>
        </section>

        <section class="about-section">
          <div class="about-content">
            <img src="../../image/about-2.jpg" alt="Hình ảnh sứ mệnh" />
          </div>
          <div class="mission-content">
            <h2>Sứ mệnh và Triết lý</h2>
            <p>Sứ mệnh của Barber Shop là trở thành điểm đến tin cậy hàng đầu cho quý ông, nơi chất lượng dịch vụ và trải nghiệm khách hàng luôn được đặt lên hàng đầu. Chúng tôi không ngừng học hỏi, cải tiến và đầu tư vào trang thiết bị hiện đại nhất để mang đến những dịch vụ tốt nhất.</p>
            <p>Triết lý của chúng tôi: "Không chỉ là cắt tóc, đó là một trải nghiệm". Chúng tôi tạo ra một không gian thoải mái, chuyên nghiệp và thân thiện, nơi bạn có thể gác lại những bộn bề cuộc sống và tận hưởng khoảnh khắc được chăm sóc chu đáo.</p>
          </div>
        </section>

        <section class="team-section">
          <h2>Đội ngũ của chúng tôi</h2>
          <div class="team-members">
            <div class="team-member">
              <img src="../../image/artist1.jpg"/>
              <h3>Lê Hiếu</h3>
              <p>Chuyên gia tạo kiểu</p>
            </div>
            <div class="team-member">
              <img src="../../image/tho-cat-toc-2.png"/>
              <h3>Ngọc Đăng</h3>
              <p>Chuyên gia cắt fade</p>
            </div>
            <div class="team-member">
              <img src="../../image/thu-cat-toc-3.jpg"/>
              <h3>Tuấn Anh</h3>
              <p>Chuyên gia cạo râu nghệ thuật</p>
            </div>
          </div>
        </section>

      </div>
    </main>

   <footer style="background-color: #1a1a1a; color: #fff; text-align: center; padding: 20px;">
         <p> Barber Shop. All rights reserved.</p>
    </footer>

    <script src="../../js/js-client/script.js"></script>
  </body>
</html>