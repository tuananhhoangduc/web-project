<?php
session_start();
require_once 'db_connect.php';

// Kiểm tra bảo mật: Nếu chưa đăng nhập thì không cho lưu
if (!isset($_SESSION['user_id'])) {
    die("Bạn cần đăng nhập để thực hiện chức năng này.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Nhận dữ liệu từ form (khớp với các name="..." trong HTML)
    $customer_id = $_SESSION['user_id'];
    $branch_id   = $_POST['branch_id'];
    $service_id  = $_POST['service_id'];
    $stylist_id  = !empty($_POST['stylist_id']) ? $_POST['stylist_id'] : null; // Thợ có thể để trống
    $date        = $_POST['appointment_date'];
    $time        = $_POST['appointment_time'];
    $notes       = htmlspecialchars($_POST['notes']); // Tránh lỗi bảo mật XSS

    try {
        // 2. Câu lệnh SQL để lưu vào bảng appointments
        $sql = "INSERT INTO appointments (customer_id, branch_id, service_id, stylist_id, appointment_date, appointment_time, notes, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$customer_id, $branch_id, $service_id, $stylist_id, $date, $time, $notes]);

        // 3. Thông báo thành công và đẩy về trang chủ (hoặc trang lịch sử)
        echo "<script>
            alert('Đặt lịch thành công! Chúng tôi sẽ sớm liên hệ xác nhận.');
            window.location.href = '../frontend/html/html-client/index.php';
        </script>";
        
    } catch(PDOException $e) {
        // Nếu lỗi (ví dụ: trùng lịch do ai đó nhanh tay hơn)
        die("Lỗi hệ thống: " . $e->getMessage());
    }
}
?>