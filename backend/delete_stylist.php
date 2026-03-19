<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db_connect.php';

if (isset($_GET['id'])) {
    $stylist_id = $_GET['id'];
    
    try {
        $conn->beginTransaction();

        $stmt_find = $conn->prepare("SELECT user_id FROM stylists WHERE stylist_id = ?");
        $stmt_find->execute([$stylist_id]);
        $row = $stmt_find->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $user_id = $row['user_id'];
            $stmt_del_stylist = $conn->prepare("DELETE FROM stylists WHERE stylist_id = ?");
            $stmt_del_stylist->execute([$stylist_id]);

            $stmt_del_user = $conn->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt_del_user->execute([$user_id]);
        }

        $conn->commit();
        echo json_encode(["status" => "success", "message" => "Đã xóa thợ cắt thành công!"]);
    } catch(PDOException $e) {
        $conn->rollBack();
        echo json_encode(["status" => "error", "message" => "Không thể xóa! Thợ cắt này đang có lịch hẹn. Gợi ý: Hãy đổi trạng thái thành 'Nghỉ phép'."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Thiếu ID thợ cắt."]);
}
?>