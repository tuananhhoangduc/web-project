<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../db_connect.php';

$stylist_id = isset($_GET['stylist_id']) ? intval($_GET['stylist_id']) : 0;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 5;
$offset = ($page - 1) * $per_page;

if (!$stylist_id) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu ID thợ']);
    exit;
}

try {
    // Lấy rating trung bình và thống kê
    $stmt = $conn->prepare("
        SELECT 
            AVG(r.rating) as avg_rating,
            COUNT(r.review_id) as total_reviews,
            SUM(CASE WHEN r.rating = 5 THEN 1 ELSE 0 END) as count_5_stars,
            SUM(CASE WHEN r.rating = 4 THEN 1 ELSE 0 END) as count_4_stars,
            SUM(CASE WHEN r.rating = 3 THEN 1 ELSE 0 END) as count_3_stars,
            SUM(CASE WHEN r.rating = 2 THEN 1 ELSE 0 END) as count_2_stars,
            SUM(CASE WHEN r.rating = 1 THEN 1 ELSE 0 END) as count_1_star
        FROM reviews r
        WHERE r.stylist_id = ?
    ");
    $stmt->execute([$stylist_id]);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    // Lấy tổng số đánh giá
    $stmt = $conn->prepare("
        SELECT COUNT(*) as total FROM reviews WHERE stylist_id = ?
    ");
    $stmt->execute([$stylist_id]);
    $countResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = $countResult['total'] ?? 0;

    // Lấy danh sách đánh giá với phân trang - sử dụng LIMIT với số nguyên
    $limit_query = "
        SELECT 
            r.review_id,
            r.rating,
            r.comment,
            r.created_at,
            u.full_name as customer_name
        FROM reviews r
        JOIN users u ON r.customer_id = u.user_id
        WHERE r.stylist_id = ?
        ORDER BY r.created_at DESC
        LIMIT " . intval($offset) . ", " . intval($per_page);
    
    $stmt = $conn->prepare($limit_query);
    $stmt->execute([$stylist_id]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'stats' => [
            'average_rating' => round($stats['avg_rating'] ?? 0, 1),
            'total_reviews' => (int)($stats['total_reviews'] ?? 0),
            'star_distribution' => [
                5 => (int)($stats['count_5_stars'] ?? 0),
                4 => (int)($stats['count_4_stars'] ?? 0),
                3 => (int)($stats['count_3_stars'] ?? 0),
                2 => (int)($stats['count_2_stars'] ?? 0),
                1 => (int)($stats['count_1_star'] ?? 0)
            ]
        ],
        'reviews' => $reviews,
        'pagination' => [
            'current_page' => (int)$page,
            'total_pages' => ceil($total / $per_page),
            'total_items' => (int)$total
        ]
    ]);

} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>