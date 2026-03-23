<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die("Bạn cần đăng nhập.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_SESSION['user_id'];
    $branch_id   = $_POST['branch_id'];
    $service_id  = $_POST['service_id'];
    $stylist_id  = !empty($_POST['stylist_id']) ? $_POST['stylist_id'] : null;
    $date        = $_POST['appointment_date'];
    $time        = $_POST['appointment_time']; // Định dạng H:i:s
    $notes       = htmlspecialchars($_POST['notes']);

    try {
        $conn->beginTransaction();

        // 1. Lấy giá tiền và thời lượng dịch vụ
        $stmt_srv = $conn->prepare("SELECT price, duration FROM services WHERE service_id = ?");
        $stmt_srv->execute([$service_id]);
        $service = $stmt_srv->fetch();
        $total_price = $service['price'] ?? 0;
        $duration = $service['duration'] ?? 30; // Mặc định 30 phút nếu không có

        // 2. Lưu vào bảng appointments
        $sql_app = "INSERT INTO appointments (customer_id, branch_id, service_id, stylist_id, appointment_date, appointment_time, notes, total_price, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        $stmt_app = $conn->prepare($sql_app);
        $stmt_app->execute([$customer_id, $branch_id, $service_id, $stylist_id, $date, $time, $notes, $total_price]);
        
        $appointment_id = $conn->lastInsertId();

        // 3. TỰ ĐỘNG CẬP NHẬT LỊCH THỢ (stylist_schedules)
        if ($stylist_id) {
            // Tính giờ kết thúc = Giờ bắt đầu + thời lượng dịch vụ
            $end_time = date('H:i:s', strtotime($time . " + $duration minutes"));
            
            $sql_sch = "INSERT INTO stylist_schedules (stylist_id, appointment_id, work_date, start_time, end_time) 
                        VALUES (?, ?, ?, ?, ?)";
            $stmt_sch = $conn->prepare($sql_sch);
            $stmt_sch->execute([$stylist_id, $appointment_id, $date, $time, $end_time]);
        }

        $conn->commit();
        echo "<script>alert('Đặt lịch thành công!'); window.location.href = '../frontend/html/html-client/history.php';</script>";
    } catch(Exception $e) {
        $conn->rollBack();
        die("Lỗi: " . $e->getMessage());
    }
}
?>