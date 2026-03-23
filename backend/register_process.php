<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Nhận dữ liệu từ form FE (register.html)
    $full_name = trim($_POST['fullname']); 
    $email = trim($_POST['email']); 
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    // 2. Mã hóa mật khẩu bảo mật
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // 3. Chuẩn bị câu lệnh SQL khớp 100% với CSDL mới
        $sql = "INSERT INTO users (full_name, email, phone, password, role) VALUES (?, ?, ?, ?, 'customer')";
        $stmt = $conn->prepare($sql);
        
        // 4. Thực thi lưu dữ liệu
        $stmt->execute([$full_name, $email, $phone, $hashed_password]);
        
        // 5. Thông báo thành công và đẩy về trang đăng nhập
        echo "<script>
            alert('Đăng ký tài khoản thành công! Vui lòng đăng nhập.'); 
            window.location.href='../frontend/html/html-client/login.html';
        </script>";
    } catch(PDOException $e) {
        // Bắt lỗi trùng Email hoặc SĐT (Do ta đã set UNIQUE trong DB)
        echo "<script>
            alert('Lỗi: Email hoặc Số điện thoại này đã được đăng ký!'); 
            window.history.back();
        </script>";
    }
}
?>