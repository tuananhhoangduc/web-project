<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stylist_id = $_POST['stylist_id'];
    $name       = trim($_POST['stylist_name']);
    $phone      = trim($_POST['stylist_phone']);
    $branch     = empty($_POST['branch_id']) ? NULL : $_POST['branch_id'];
    
    // Dịch trạng thái từ Form sang Tiếng Việt chuẩn của DB
    $status_input = $_POST['stylist_status'] ?? 'active';
    $status = ($status_input == 'active') ? 'Đang làm việc' : 'Nghỉ phép';

    try {
        // BẮT ĐẦU TRANSACTION
        $conn->beginTransaction();

        // 1. Tìm user_id của thợ này từ bảng stylists
        $stmt_find = $conn->prepare("SELECT user_id FROM stylists WHERE stylist_id = ?");
        $stmt_find->execute([$stylist_id]);
        $row = $stmt_find->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new Exception("Không tìm thấy dữ liệu thợ cắt này.");
        }
        $user_id = $row['user_id'];

        // 2. Cập nhật bảng users (Tên và SĐT)
        $sql_user = "UPDATE users SET full_name = ?, phone = ? WHERE user_id = ?";
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->execute([$name, $phone, $user_id]);

        // 3. Cập nhật bảng stylists (Chi nhánh và Trạng thái)
        $sql_stylist = "UPDATE stylists SET branch_id = ?, status = ? WHERE stylist_id = ?";
        $stmt_stylist = $conn->prepare($sql_stylist);
        $stmt_stylist->execute([$branch, $status, $stylist_id]);

        // NẾU KHÔNG CÓ LỖI -> CHỐT LƯU
        $conn->commit();

        echo "<script>
                alert('Cập nhật thông tin thợ cắt thành công!'); 
                window.location.href = '../frontend/html/html-admin/stylist-management.php';
              </script>";
    } catch(Exception $e) {
        $conn->rollBack();
        die("Lỗi hệ thống: " . $e->getMessage());
    }
} else {
    echo "Truy cập không hợp lệ!";
}
?>