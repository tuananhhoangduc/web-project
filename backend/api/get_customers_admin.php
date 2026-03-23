<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../db_connect.php';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT user_id, full_name, phone, email, DATE_FORMAT(created_at, '%d/%m/%Y') as created_at FROM users WHERE role = 'customer'";
$params = [];
if (!empty($search)) {
    $sql .= " AND (full_name LIKE ? OR phone LIKE ? OR email LIKE ?)";
    $params = ["%$search%", "%$search%", "%$search%"];
}
$sql .= " ORDER BY user_id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
?>