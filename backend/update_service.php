<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($data && !empty($data['service_id'])) {
    $id = $data['service_id'];
    $name = trim($data['service_name'] ?? '');
    $price = $data['service_price'] ?? 0;
    $duration = $data['service_duration'] ?? 0;
    $description = trim($data['service_description'] ?? '');

    try {
        $sql = "UPDATE services SET service_name = ?, price = ?, duration = ?, description = ? WHERE service_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $price, $duration, $description, $id]);

        echo json_encode(["status" => "success", "message" => "Cập nhật dịch vụ thành công!"]);
    } catch(PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Lỗi hệ thống: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Dữ liệu gửi lên không hợp lệ!"]);
}
?>