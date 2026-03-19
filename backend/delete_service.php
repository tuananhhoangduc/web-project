<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $sql = "DELETE FROM services WHERE service_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

        echo json_encode(["status" => "success", "message" => "Đã xóa dịch vụ thành công!"]);
    } catch(PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Lỗi: Không thể xóa dịch vụ này. Có thể nó đang liên kết với dữ liệu khác."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Lỗi: Không tìm thấy ID dịch vụ cần xóa."]);
}
?>