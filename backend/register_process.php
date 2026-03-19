<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $full_name = trim($data['fullname']); 
    $email = trim($data['email']); 
    $phone = trim($data['phone']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users (full_name, email, phone, password, role) VALUES (?, ?, ?, ?, 'customer')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$full_name, $email, $phone, $password]);
        
        echo json_encode(["status" => "success", "message" => "Đăng ký tài khoản thành công!"]);
    } catch(PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Email hoặc Số điện thoại này đã được đăng ký!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Yêu cầu không hợp lệ!"]);
}
?>