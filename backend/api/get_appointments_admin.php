<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../db_connect.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$limit = 3; 
$offset = ($page - 1) * $limit;

$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : ''; 

$params = [];
$whereClauses = ["1=1"]; 

if (!empty($dateFilter)) {
    $whereClauses[] = "DATE(a.appointment_date) = ?";
    $params[] = $dateFilter;
}
if (!empty($statusFilter)) {
    $whereClauses[] = "a.status = ?";
    $params[] = $statusFilter;
}
if (!empty($search)) {
    $whereClauses[] = "(u.full_name LIKE ? OR u.phone LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$whereSql = " WHERE " . implode(" AND ", $whereClauses);

try {
    $sqlCount = "SELECT COUNT(*) as total 
                 FROM appointments a 
                 JOIN users u ON a.customer_id = u.user_id 
                 $whereSql";
    
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->execute($params);
    $totalRows = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $limit);

    $sqlData = "SELECT a.*, u.full_name as cust_name, u.phone as cust_phone, 
                       s.service_name, b.branch_name, st_u.full_name as stylist_name
                FROM appointments a 
                JOIN users u ON a.customer_id = u.user_id 
                JOIN services s ON a.service_id = s.service_id
                JOIN branches b ON a.branch_id = b.branch_id 
                LEFT JOIN stylists st ON a.stylist_id = st.stylist_id
                LEFT JOIN users st_u ON st.user_id = st_u.user_id 
                $whereSql";

    $sqlData .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC 
                  LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

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