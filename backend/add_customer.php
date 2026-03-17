<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name  = trim($_POST['customer_name']);
    $phone = trim($_POST['customer_phone']);
    $email = trim($_POST['customer_email']);
    
    // Tạo mật khẩu mặc định (ví dụ 123456) để khách có thể tự đăng nhập sau này
    $password = password_hash('123456', PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users (full_name, phone, email, password, role) VALUES (?, ?, ?, ?, 'customer')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $phone, $email, $password]);

        echo "<script>alert('Thêm khách hàng thành công!'); window.location.href = '../frontend/html/html-admin/customer-management.php';</script>";
    } catch(PDOException $e) {
        die("Lỗi (Có thể số điện thoại/email đã tồn tại): " . $e->getMessage());
    }
}
?>