<?php
session_start();
require_once 'db_connect.php';

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $appointment_id = $_GET['id'];
    $customer_id = $_SESSION['user_id'];

    try {
        $conn->beginTransaction();

        // 1. Cập nhật trạng thái lịch hẹn thành 'cancelled'
        $sql_app = "UPDATE appointments SET status = 'cancelled' WHERE appointment_id = ? AND customer_id = ? AND status = 'pending'";
        $stmt_app = $conn->prepare($sql_app);
        $stmt_app->execute([$appointment_id, $customer_id]);

        // 2. XÓA bản ghi tương ứng trong lịch trình của thợ để giải phóng giờ
        $sql_sch = "DELETE FROM stylist_schedules WHERE appointment_id = ?";
        $stmt_sch = $conn->prepare($sql_sch);
        $stmt_sch->execute([$appointment_id]);

        $conn->commit();
        echo "<script>alert('Bạn đã hủy lịch hẹn thành công!'); window.location.href = '../frontend/html/html-client/history.php';</script>";
    } catch(Exception $e) {
        $conn->rollBack();
        die("Lỗi: " . $e->getMessage());
    }
}
?>