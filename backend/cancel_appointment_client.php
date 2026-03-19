<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db_connect.php';

// Nhận ID lịch hẹn và ID khách hàng từ URL
if (isset($_GET['id']) && isset($_GET['customer_id'])) {
    $appointment_id = $_GET['id'];
    $customer_id = $_GET['customer_id'];

    try {
        $conn->beginTransaction();

        // 1. Cập nhật trạng thái lịch hẹn thành 'cancelled'
        $sql_app = "UPDATE appointments SET status = 'cancelled' WHERE appointment_id = ? AND customer_id = ? AND status = 'pending'";
        $stmt_app = $conn->prepare($sql_app);
        $stmt_app->execute([$appointment_id, $customer_id]);

        // Kiểm tra xem có thực sự cập nhật được dòng nào không
        if ($stmt_app->rowCount() > 0) {
            // 2. Xóa bản ghi tương ứng trong lịch trình của thợ để giải phóng giờ
            $sql_sch = "DELETE FROM stylist_schedules WHERE appointment_id = ?";
            $stmt_sch = $conn->prepare($sql_sch);
            $stmt_sch->execute([$appointment_id]);

            $conn->commit();
            echo json_encode(["status" => "success", "message" => "Bạn đã hủy lịch hẹn thành công!"]);
        } else {
            // Nếu không cập nhật được (sai ID, sai người, hoặc trạng thái không còn là pending)
            $conn->rollBack();
            echo json_encode(["status" => "error", "message" => "Không thể hủy! Lịch hẹn không tồn tại, không thuộc về bạn, hoặc đã được xử lý."]);
        }
    } catch(Exception $e) {
        $conn->rollBack();
        echo json_encode(["status" => "error", "message" => "Lỗi hệ thống: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Thiếu ID lịch hẹn hoặc ID khách hàng!"]);
}
?>