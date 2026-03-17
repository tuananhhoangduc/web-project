<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // FE vẫn gửi sang biến tên là 'username' (do thuộc tính name="username" ở ô nhập)
    // Nhưng bản chất nó là Email hoặc SĐT do khách nhập vào
    $login_input = trim($_POST['username']); 
    $password = $_POST['password'];

    try {
        // SỬA Ở ĐÂY: Tìm tài khoản khớp với cột 'email' HOẶC cột 'phone'
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
        $stmt->execute([$login_input, $login_input]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Kiểm tra mật khẩu
        if ($user && password_verify($password, $user['password'])) {
            
            // Lưu Session (Thẻ chứng minh)
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            // Điều hướng theo quyền
            if ($user['role'] === 'admin') {
                header("Location: ../frontend/html/html-admin/admin-dashboard.html");
            } elseif ($user['role'] === 'stylist') {
                header("Location: ../frontend/html/html-barber/barber-dashboard.html"); 
            } else {
                header("Location: ../frontend/html/html-client/index.php"); // Khách hàng
            }
            exit();

        } else {
            echo "<script>alert('Sai Email/SĐT hoặc mật khẩu!'); window.history.back();</script>";
        }
    } catch(PDOException $e) {
        die("Lỗi hệ thống: " . $e->getMessage());
    }
}
?>