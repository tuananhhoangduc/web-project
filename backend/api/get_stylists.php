<?php
require_once '../db_connect.php';
header('Content-Type: application/json; charset=utf-8');

if (isset($_GET['branch_id'])) {
    $branch_id = $_GET['branch_id'];

    try {
        // Lấy VIP stylists
        $sqlVip = "SELECT stylist_id FROM appointments GROUP BY stylist_id ORDER BY COUNT(*) DESC LIMIT 3";
        $stmtVip = $conn->query($sqlVip);
        $vipIds = $stmtVip->fetchAll(PDO::FETCH_COLUMN); 
    
        // ✅ Query từ stylists table
        $sql = "SELECT st.stylist_id, u.full_name 
                FROM stylists st 
                JOIN users u ON st.user_id = u.user_id 
                WHERE st.branch_id = ? AND st.status IN ('Đang làm việc', 'available')
                ORDER BY u.full_name ASC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$branch_id]);
        $stylists = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($stylists as &$st) {
            $st['is_vip'] = in_array($st['stylist_id'], $vipIds);
        }

        echo json_encode(['status' => 'success', 'data' => $stylists]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu branch_id']);
}
?>