<?php
require_once 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Lệnh SQL để xóa chi nhánh theo ID
        $sql = "DELETE FROM branches WHERE branch_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

        // Xóa xong thì báo thành công và quay lại trang quản lý
        echo "<script>
                alert('Đã xóa chi nhánh thành công!'); 
                window.location.href = '../frontend/html/html-admin/branch-management.php';
              </script>";
    } catch(PDOException $e) {
        // Nếu chi nhánh đang có Thợ hoặc Lịch hẹn thì không được xóa (tránh lỗi dữ liệu)
        echo "<script>
                alert('Không thể xóa! Chi nhánh này đang có Thợ hoặc Lịch hẹn. Hãy xóa thợ/lịch hẹn trước.'); 
                window.location.href = '../frontend/html/html-admin/branch-management.php';
              </script>";
    }
} else {
    echo "Không tìm thấy ID chi nhánh.";
}
?>