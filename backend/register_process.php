<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    // Mã hóa mật khẩu để bảo mật
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users (full_name, phone, username, password, role) VALUES (?, ?, ?, ?, 'customer')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$full_name, $phone, $username, $password]);
        
        echo "<script>alert('Đăng ký thành công!'); window.location.href='../frontend/html/login.html';</script>";
    } catch(PDOException $e) {
        // Nếu trùng số điện thoại hoặc username, CSDL sẽ báo lỗi thông qua UNIQUE KEY
        echo "Lỗi: " . $e->getMessage();
    }
}
?>