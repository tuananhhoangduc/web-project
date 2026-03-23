<?php
session_start();
require_once 'db_connect.php';

if (isset($_GET['id'])) {
    $stylist_id = $_GET['id'];
    
    try {
        $conn->beginTransaction();

        // 1. Tìm user_id của thợ
        $stmt_find = $conn->prepare("SELECT user_id FROM stylists WHERE stylist_id = ?");
        $stmt_find->execute([$stylist_id]);
        $row = $stmt_find->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $user_id = $row['user_id'];

            // 2. Xóa bên bảng stylists trước (vì nó chứa khóa ngoại)
            $stmt_del_stylist = $conn->prepare("DELETE FROM stylists WHERE stylist_id = ?");
            $stmt_del_stylist->execute([$stylist_id]);

            // 3. Xóa bên bảng users
            $stmt_del_user = $conn->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt_del_user->execute([$user_id]);
        }

        $conn->commit();

        echo "<script>
                alert('Đã xóa thợ cắt thành công!'); 
                window.location.href = '../frontend/html/html-admin/stylist-management.php';
              </script>";
    } catch(PDOException $e) {
        $conn->rollBack();
        // Bắt lỗi khóa ngoại cực xịn sò
        echo "<script>
                alert('Không thể xóa! Thợ cắt này đang có lịch hẹn lưu trong hệ thống. Gợi ý: Hãy Sửa thông tin và đổi trạng thái thành \"Nghỉ phép\" thay vì Xóa.'); 
                window.location.href = '../frontend/html/html-admin/stylist-management.php';
              </script>";
    }
} else {
    echo "Lỗi: Không tìm thấy ID thợ cắt.";
}
?>