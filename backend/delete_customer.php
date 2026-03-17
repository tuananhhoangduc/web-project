<?php
session_start();
require_once 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Lớp bảo vệ: Chỉ xóa khi user đó thực sự là 'customer'
        $sql = "DELETE FROM users WHERE user_id = ? AND role = 'customer'";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

        // Xóa thành công thì báo cáo và quay xe về trang quản lý
        echo "<script>
                alert('Đã xóa khách hàng thành công!'); 
                window.location.href = '../frontend/html/html-admin/customer-management.php';
              </script>";
    } catch(PDOException $e) {
        // Bắt lỗi: Nếu khách hàng này ĐÃ CÓ lịch hẹn trong DB, MySQL sẽ chặn không cho xóa
        // để bảo vệ toàn vẹn dữ liệu (không bị mồ côi lịch hẹn).
        echo "<script>
                alert('Không thể xóa! Khách hàng này đang có dữ liệu Lịch hẹn trong hệ thống.'); 
                window.location.href = '../frontend/html/html-admin/customer-management.php';
              </script>";
    }
} else {
    echo "Lỗi: Không tìm thấy ID khách hàng cần xóa.";
}
?>