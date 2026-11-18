<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../db_connect.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$limit = 5; 
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$params = [];

$sqlCount = "SELECT COUNT(*) as total FROM users WHERE role = 'customer'";

$sqlData = "SELECT user_id, full_name, phone, email, DATE_FORMAT(created_at, '%d/%m/%Y') as created_at FROM users WHERE role = 'customer'";

if (!empty($search)) {
    $searchCondition = " AND (full_name LIKE ? OR phone LIKE ? OR email LIKE ?)";
    $sqlCount .= $searchCondition;
    $sqlData .= $searchCondition;
    $params = ["%$search%", "%$search%", "%$search%"];
}

try {
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->execute($params);
    $totalRows = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $limit); 

    $sqlData .= " ORDER BY user_id DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
    
    $stmtData = $conn->prepare($sqlData);
    $stmtData->execute($params);
    $data = $stmtData->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success', 
        'data' => $data, 
        'total_pages' => $totalPages, 
        'current_page' => $page
    ]);

} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi CSDL: ' . $e->getMessage()]);
}
?>