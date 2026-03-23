<?php
session_start();
require_once 'db_connect.php';

// Kiểm tra xem có ID được gửi tới không
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Thực hiện lệnh xóa dịch vụ theo ID
        $sql = "DELETE FROM services WHERE service_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

        // Thông báo thành công và quay lại trang danh sách
        echo "<script>
                alert('Đã xóa dịch vụ thành công!'); 
                window.location.href = '../frontend/html/html-admin/service-management.php';
              </script>";
    } catch(PDOException $e) {
        // Lỗi này thường xảy ra nếu dịch vụ này đang được liên kết với một Lịch hẹn (Appointments)
        // Trong CSDL của bạn, khóa ngoại đang để ON DELETE SET NULL nên sẽ không bị lỗi này, 
        // nhưng phòng hờ nếu bạn đổi cấu trúc sau này.
        echo "<script>
                alert('Lỗi: Không thể xóa dịch vụ này. Có thể nó đang liên kết với dữ liệu khác.'); 
                window.location.href = '../frontend/html/html-admin/service-management.php';
              </script>";
    }
} else {
    echo "Không tìm thấy ID dịch vụ cần xóa.";
}
?>