<?php
session_start();
require_once 'db_connect.php';

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $appointment_id = $_GET['id'];
    $customer_id = $_SESSION['user_id'];

    // Chỉ cho phép hủy lịch của chính mình và đang ở trạng thái pending
    $sql = "UPDATE appointments SET status = 'cancelled' WHERE appointment_id = ? AND customer_id = ? AND status = 'pending'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$appointment_id, $customer_id]);

    echo "<script>
            alert('Bạn đã hủy lịch hẹn thành công!');
            window.location.href = '../frontend/html/html-client/history.php';
          </script>";
}
?>