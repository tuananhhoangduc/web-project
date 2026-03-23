<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../db_connect.php';
$sql = "SELECT a.*, u.full_name as cust_name, u.phone as cust_phone, s.service_name, b.branch_name, st_u.full_name as stylist_name
        FROM appointments a JOIN users u ON a.customer_id = u.user_id JOIN services s ON a.service_id = s.service_id
        JOIN branches b ON a.branch_id = b.branch_id LEFT JOIN stylists st ON a.stylist_id = st.stylist_id
        LEFT JOIN users st_u ON st.user_id = st_u.user_id WHERE 1=1";
$params = [];
if (!empty($_GET['date'])) { $sql .= " AND DATE(a.appointment_date) = ?"; $params[] = $_GET['date']; }
if (!empty($_GET['status'])) { $sql .= " AND a.status = ?"; $params[] = $_GET['status']; }
$sql .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
?>