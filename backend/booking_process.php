<?php
session_start();
require_once 'db_connect.php';

// Kiểm tra bảo mật
if (!isset($_SESSION['user_id'])) {
    die("Bạn cần đăng nhập để thực hiện chức năng này.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_SESSION['user_id'];
    $branch_id   = $_POST['branch_id'];
    $service_id  = $_POST['service_id'];
    $stylist_id  = !empty($_POST['stylist_id']) ? $_POST['stylist_id'] : null;
    $date        = $_POST['appointment_date'];
    $time        = $_POST['appointment_time'];
    $notes       = htmlspecialchars($_POST['notes']);

    try {
        $conn->beginTransaction();

        // 1. Lấy giá tiền chuẩn xác của dịch vụ từ Database
        $stmt_price = $conn->prepare("SELECT price FROM services WHERE service_id = ?");
        $stmt_price->execute([$service_id]);
        $service = $stmt_price->fetch();
        $total_price = $service ? $service['price'] : 0; // Tránh lỗi nếu không tìm thấy dịch vụ

        // 2. Lưu vào bảng appointments (Đã bổ sung total_price)
        $sql = "INSERT INTO appointments (customer_id, branch_id, service_id, stylist_id, appointment_date, appointment_time, notes, total_price, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$customer_id, $branch_id, $service_id, $stylist_id, $date, $time, $notes, $total_price]);

        $conn->commit();

        // 3. Thông báo và chuyển hướng
        echo "<script>
            alert('Đặt lịch thành công! Chúng tôi sẽ sớm liên hệ xác nhận.');
            window.location.href = '../frontend/html/html-client/index.php';
        </script>";
        
    } catch(PDOException $e) {
        $conn->rollBack();
        die("Lỗi hệ thống: " . $e->getMessage());
    }
}
?>