<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db_connect.php';

// Hứng dữ liệu JSON
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra xem có gửi ID lên không
if ($data && !empty($data['branch_id'])) {
    $id      = $data['branch_id'];
    $name    = trim($data['branch_name'] ?? '');
    $address = trim($data['branch_address'] ?? '');
    $phone   = trim($data['branch_phone'] ?? '');
    $email   = trim($data['branch_email'] ?? '');

    try {
        $sql = "UPDATE branches SET branch_name = ?, address = ?, phone = ?, email = ? WHERE branch_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $address, $phone, $email, $id]);

        echo json_encode(["status" => "success", "message" => "Cập nhật chi nhánh thành công!"]);
    } catch(PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Lỗi hệ thống: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Lỗi: Không tìm thấy ID chi nhánh cần cập nhật!"]);
}
?>