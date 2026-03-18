<?php
session_start();
require_once 'db_connect.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $appointment_id = (int)$_GET['id'];
    $new_status = $_GET['status']; // Nhận 'confirmed', 'cancelled', hoặc 'completed'

    try {
        $sql = "UPDATE appointments SET status = ? WHERE appointment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$new_status, $appointment_id]);

        // Tạo câu thông báo bằng tiếng Việt
        if ($new_status == 'confirmed') {
            $message = 'Đã XÁC NHẬN lịch hẹn thành công!';
        } elseif ($new_status == 'completed') {
            $message = 'Đã chốt HOÀN THÀNH lịch hẹn!';
        } else {
            $message = 'Đã HỦY lịch hẹn!';
        }

        echo "<script>
                alert('$message'); 
                window.location.href = '../frontend/html/html-admin/salon-appointments.php';
              </script>";
    } catch(PDOException $e) {
        die("Lỗi hệ thống: " . $e->getMessage());
    }
} else {
    echo "<script>
            alert('Lỗi: Thiếu dữ liệu!'); 
            window.location.href = '../frontend/html/html-admin/salon-appointments.php';
          </script>";
}
?>