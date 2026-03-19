<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db_connect.php';

// Hứng dữ liệu JSON từ Body
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    // Lấy customer_id từ cục data gửi lên (Trong thực tế hệ thống lớn, id này sẽ được giải mã từ Token)
    $customer_id = $data['customer_id'] ?? null; 
    $branch_id   = $data['branch_id'] ?? null;
    $service_id  = $data['service_id'] ?? null;
    $stylist_id  = !empty($data['stylist_id']) ? $data['stylist_id'] : null;
    $date        = $data['appointment_date'] ?? null;
    $time        = $data['appointment_time'] ?? null; 
    $notes       = htmlspecialchars($data['notes'] ?? '');

    // Kiểm tra dữ liệu bắt buộc
    if (!$customer_id || !$branch_id || !$service_id || !$date || !$time) {
        echo json_encode(["status" => "error", "message" => "Thiếu thông tin đặt lịch bắt buộc!"]);
        exit;
    }

    try {
        $conn->beginTransaction();

        // 1. Lấy giá tiền và thời lượng dịch vụ
        $stmt_srv = $conn->prepare("SELECT price, duration FROM services WHERE service_id = ?");
        $stmt_srv->execute([$service_id]);
        $service = $stmt_srv->fetch();
        $total_price = $service['price'] ?? 0;
        $duration = $service['duration'] ?? 30;

        // 2. Lưu vào bảng appointments
        $sql_app = "INSERT INTO appointments (customer_id, branch_id, service_id, stylist_id, appointment_date, appointment_time, notes, total_price, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        $stmt_app = $conn->prepare($sql_app);
        $stmt_app->execute([$customer_id, $branch_id, $service_id, $stylist_id, $date, $time, $notes, $total_price]);
        
        $appointment_id = $conn->lastInsertId();

        // 3. Cập nhật lịch thợ (stylist_schedules)
        if ($stylist_id) {
            $end_time = date('H:i:s', strtotime($time . " + $duration minutes"));
            
            $sql_sch = "INSERT INTO stylist_schedules (stylist_id, appointment_id, work_date, start_time, end_time) 
                        VALUES (?, ?, ?, ?, ?)";
            $stmt_sch = $conn->prepare($sql_sch);
            $stmt_sch->execute([$stylist_id, $appointment_id, $date, $time, $end_time]);
        }

        $conn->commit();
        echo json_encode([
            "status" => "success", 
            "message" => "Đặt lịch thành công!",
            "appointment_id" => $appointment_id
        ]);
    } catch(Exception $e) {
        $conn->rollBack();
        echo json_encode(["status" => "error", "message" => "Lỗi: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Dữ liệu gửi lên không hợp lệ!"]);
}
?>