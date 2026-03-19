<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $name   = trim($data['stylist_name'] ?? '');
    $phone  = trim($data['stylist_phone'] ?? '');
    $branch = empty($data['branch_id']) ? NULL : $data['branch_id'];
    
    // Đổi value sang chữ tiếng Việt khớp với Database
    $status_input = $data['stylist_status'] ?? 'active';
    $status = ($status_input == 'active') ? 'Đang làm việc' : 'Nghỉ phép';

    // Tạo mật khẩu mặc định
    $password = password_hash('123456', PASSWORD_DEFAULT);

    // FIX LỖI: Tạo một email ảo ngẫu nhiên theo thời gian thực để không bao giờ bị trùng
    $fake_email = "stylist_" . time() . "_" . rand(100, 999) . "@barber.local";

    try {
        // BẮT ĐẦU TRANSACTION
        $conn->beginTransaction();

        // Đã bổ sung cột email và biến $fake_email vào câu lệnh INSERT
        $sql1 = "INSERT INTO users (full_name, phone, email, password, role) VALUES (?, ?, ?, ?, 'stylist')";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute([$name, $phone, $fake_email, $password]);
        
        $user_id = $conn->lastInsertId();

        // Thêm dữ liệu vào bảng stylists
        $sql2 = "INSERT INTO stylists (user_id, branch_id, status) VALUES (?, ?, ?)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$user_id, $branch, $status]);

        // CHỐT LƯU VÀO DATABASE
        $conn->commit();

        echo json_encode(["status" => "success", "message" => "Thêm thợ cắt thành công!"]);
    } catch(PDOException $e) {
        // NẾU CÓ LỖI -> QUAY XE
        $conn->rollBack(); 
        echo json_encode(["status" => "error", "message" => "Lỗi hệ thống: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Truy cập không hợp lệ hoặc thiếu dữ liệu!"]);
}
?>