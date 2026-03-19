<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db_connect.php';

// Hứng dữ liệu JSON từ Body của Request
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $name    = trim($data['branch_name'] ?? '');
    $address = trim($data['branch_address'] ?? '');
    $phone   = trim($data['branch_phone'] ?? '');
    $email   = trim($data['branch_email'] ?? '');

    try {
        $sql = "INSERT INTO branches (branch_name, address, phone, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $address, $phone, $email]);

        // Trả về JSON thành công
        echo json_encode(["status" => "success", "message" => "Thêm chi nhánh mới thành công!"]);
    } catch(PDOException $e) {
        // Trả về JSON báo lỗi
        echo json_encode(["status" => "error", "message" => "Lỗi hệ thống: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Dữ liệu gửi lên không hợp lệ!"]);
}
?>