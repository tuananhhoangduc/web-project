<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name   = trim($_POST['stylist_name']);
    $phone  = trim($_POST['stylist_phone']);
    $branch = empty($_POST['branch_id']) ? NULL : $_POST['branch_id'];
    
    // Đổi value từ Form sang chữ tiếng Việt khớp với Database
    $status_input = $_POST['stylist_status'] ?? 'active';
    $status = ($status_input == 'active') ? 'Đang làm việc' : 'Nghỉ phép';

    // Tạo mật khẩu mặc định (ví dụ: 123456)
    $password = password_hash('123456', PASSWORD_DEFAULT);

    try {
        // 1. BẮT ĐẦU TRANSACTION (Khóa an toàn)
        $conn->beginTransaction();

        // 2. Thêm dữ liệu vào bảng `users` (Cấp quyền role = 'stylist')
        $sql1 = "INSERT INTO users (full_name, phone, password, role) VALUES (?, ?, ?, 'stylist')";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute([$name, $phone, $password]);
        
        // 3. Lấy cái ID của user vừa mới được tạo ra
        $user_id = $conn->lastInsertId();

        // 4. Thêm tiếp dữ liệu vào bảng `stylists` (Dùng user_id vừa lấy được)
        $sql2 = "INSERT INTO stylists (user_id, branch_id, status) VALUES (?, ?, ?)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$user_id, $branch, $status]);

        // 5. NẾU MỌI THỨ SUÔN SẺ -> CHỐT LƯU VÀO DATABASE
        $conn->commit();

        echo "<script>
                alert('Thêm thợ cắt thành công!'); 
                window.location.href = '../frontend/html/html-admin/stylist-management.php';
              </script>";
    } catch(PDOException $e) {
        // NẾU CÓ LỖI (Ví dụ trùng SĐT) -> QUAY XE, KHÔNG LƯU GÌ CẢ
        $conn->rollBack(); 
        die("Lỗi hệ thống: " . $e->getMessage());
    }
} else {
    echo "Truy cập không hợp lệ!";
}
?>