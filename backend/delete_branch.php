<?php
// Khai báo trả về chuẩn JSON
header('Content-Type: application/json; charset=utf-8');
require_once 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $sql = "DELETE FROM branches WHERE branch_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

        // Trả về JSON thành công
        echo json_encode(["status" => "success", "message" => "Đã xóa chi nhánh thành công!"]);
    } catch(PDOException $e) {
        // Trả về JSON lỗi khóa ngoại
        echo json_encode(["status" => "error", "message" => "Không thể xóa! Chi nhánh này đang có Thợ hoặc Lịch hẹn. Hãy xóa thợ/lịch hẹn trước."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Lỗi: Không tìm thấy ID chi nhánh."]);
}
?>