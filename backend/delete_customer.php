<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $sql = "DELETE FROM users WHERE user_id = ? AND role = 'customer'";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

        echo json_encode(["status" => "success", "message" => "Đã xóa khách hàng thành công!"]);
    } catch(PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Không thể xóa! Khách hàng này đang có dữ liệu Lịch hẹn trong hệ thống."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Lỗi: Không tìm thấy ID khách hàng cần xóa."]);
}
?>