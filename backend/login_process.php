<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db_connect.php';

// Hứng dữ liệu JSON thay vì $_POST
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu đầu vào!"]);
    exit;
}

$login_input = trim($data['username']); 
$password = $data['password'];

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
    $stmt->execute([$login_input, $login_input]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Tạo Token ngẫu nhiên (Hộ chiếu)
        $token = bin2hex(random_bytes(16)); 

        echo json_encode([
            "status" => "success",
            "message" => "Đăng nhập thành công!",
            "token" => $token,
            "role" => $user['role'],
            "full_name" => $user['full_name'],
            "user_id" => $user['user_id']
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Sai Email/SĐT hoặc mật khẩu!"]);
    }
} catch(PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Lỗi hệ thống: " . $e->getMessage()]);
}
?>