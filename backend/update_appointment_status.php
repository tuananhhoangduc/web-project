<?php
// 1. Khai báo trả về chuẩn JSON
header('Content-Type: application/json; charset=utf-8');
require_once 'db_connect.php';

// 2. Nhận dữ liệu từ tham số URL (Query Params)
if (isset($_GET['id']) && isset($_GET['status'])) {
    $appointment_id = (int)$_GET['id'];
    $new_status = $_GET['status']; // Nhận 'confirmed', 'cancelled', hoặc 'completed'

    try {
        // Bật Transaction để đảm bảo an toàn dữ liệu kép
        $conn->beginTransaction();

        // 3. Cập nhật trạng thái lịch hẹn
        $sql = "UPDATE appointments SET status = ? WHERE appointment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$new_status, $appointment_id]);
        
        // 4. Nếu HỦY lịch -> Xóa giờ đã khóa của thợ
        if ($new_status == 'cancelled') {
            $sql_del_sch = "DELETE FROM stylist_schedules WHERE appointment_id = ?";
            $stmt_del = $conn->prepare($sql_del_sch);
            $stmt_del->execute([$appointment_id]);
        }

        // Chốt lưu thay đổi
        $conn->commit();

        // 5. Trả về thông báo JSON thay vì dùng script alert
        if ($new_status == 'confirmed') {
            $message = 'Đã XÁC NHẬN lịch hẹn thành công!';
        } elseif ($new_status == 'completed') {
            $message = 'Đã chốt HOÀN THÀNH lịch hẹn!';
        } else {
            $message = 'Đã HỦY lịch hẹn và giải phóng lịch cho thợ!';
        }

        echo json_encode(["status" => "success", "message" => $message]);

    } catch(PDOException $e) {
        // Có lỗi thì quay xe
        $conn->rollBack();
        echo json_encode(["status" => "error", "message" => "Lỗi hệ thống: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Lỗi: Thiếu dữ liệu ID hoặc Trạng thái!"]);
}
?>