<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($data && !empty($data['customer_id'])) {
    $id    = $data['customer_id'];
    $name  = trim($data['customer_name'] ?? '');
    $phone = trim($data['customer_phone'] ?? '');
    $email = trim($data['customer_email'] ?? '');

    try {
        $sql = "UPDATE users SET full_name = ?, phone = ?, email = ? WHERE user_id = ? AND role = 'customer'";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $phone, $email, $id]);

        echo json_encode(["status" => "success", "message" => "Cập nhật thông tin khách hàng thành công!"]);
    } catch(PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Lỗi cập nhật: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu cập nhật!"]);
}
?>