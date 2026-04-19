<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../db_connect.php';

$stylist_id = $_GET['stylist_id'] ?? 0;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 5;
$offset = ($page - 1) * $per_page;

if (!$stylist_id) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu ID thợ']);
    exit;
}

try {
    // ✅ Lấy total từ appointments
    $stmt = $conn->prepare("
        SELECT COUNT(r.review_id) as total 
        FROM reviews r
        JOIN appointments a ON r.appointment_id = a.appointment_id
        WHERE a.stylist_id = ?
    ");
    $stmt->execute([$stylist_id]);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // ✅ Lấy reviews
    $query = "
        SELECT r.review_id, r.rating, r.comment, r.created_at, u.full_name as customer_name
        FROM reviews r
        JOIN appointments a ON r.appointment_id = a.appointment_id
        JOIN users u ON r.customer_id = u.user_id
        WHERE a.stylist_id = ?
        ORDER BY r.created_at DESC
        LIMIT " . intval($offset) . ", " . intval($per_page);
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$stylist_id]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ✅ Lấy stats
    $stmt = $conn->prepare("
        SELECT AVG(r.rating) as avg_r, COUNT(r.review_id) as total,
        SUM(CASE WHEN r.rating = 5 THEN 1 ELSE 0 END) as r5,
        SUM(CASE WHEN r.rating = 4 THEN 1 ELSE 0 END) as r4,
        SUM(CASE WHEN r.rating = 3 THEN 1 ELSE 0 END) as r3,
        SUM(CASE WHEN r.rating = 2 THEN 1 ELSE 0 END) as r2,
        SUM(CASE WHEN r.rating = 1 THEN 1 ELSE 0 END) as r1
        FROM reviews r
        JOIN appointments a ON r.appointment_id = a.appointment_id
        WHERE a.stylist_id = ?
    ");
    $stmt->execute([$stylist_id]);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'stats' => [
            'average_rating' => round($stats['avg_r'] ?? 0, 1),
            'total_reviews' => (int)$stats['total'],
            'star_distribution' => [
                5 => (int)$stats['r5'],
                4 => (int)$stats['r4'],
                3 => (int)$stats['r3'],
                2 => (int)$stats['r2'],
                1 => (int)$stats['r1']
            ]
        ],
        'reviews' => $reviews,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => ceil($total / $per_page),
            'total_reviews' => (int)$total
        ]
    ]);
} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>