<?php
require_once '../db_connect.php'; // Lùi 1 cấp để gọi db_connect.php
header('Content-Type: application/json; charset=utf-8');

if (isset($_GET['branch_id'])) {
    $branch_id = $_GET['branch_id'];

    try {
        // Lấy thợ thuộc branch_id này và đang làm việc
        $sql = "SELECT s.stylist_id, u.full_name 
                FROM stylists s 
                JOIN users u ON s.user_id = u.user_id 
                WHERE s.branch_id = ? AND s.status = 'Đang làm việc'";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$branch_id]);
        $stylists = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Trả về dữ liệu chuẩn JSON
        echo json_encode(['status' => 'success', 'data' => $stylists]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu branch_id']);
}
?>