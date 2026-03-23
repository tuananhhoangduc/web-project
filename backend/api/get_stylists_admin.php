<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../db_connect.php';

try {
    $sql = "SELECT s.stylist_id, s.branch_id, s.status, u.full_name, u.phone, b.branch_name 
            FROM stylists s 
            INNER JOIN users u ON s.user_id = u.user_id 
            LEFT JOIN branches b ON s.branch_id = b.branch_id 
            ORDER BY s.stylist_id DESC";
    $stmt = $conn->query($sql);
    echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
