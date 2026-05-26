<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../db_connect.php';

try {
    $sql = "
        SELECT
            st.stylist_id,
            u.full_name,
            b.branch_name,
            COALESCE(ap.total_revenue, 0) AS total_revenue,
            COALESCE(ap.total_appointments, 0) AS total_appointments,
            COALESCE(rv.average_rating, 0) AS average_rating
        FROM stylists st
        INNER JOIN users u ON st.user_id = u.user_id
        LEFT JOIN branches b ON st.branch_id = b.branch_id
        LEFT JOIN (
            SELECT stylist_id, SUM(total_price) AS total_revenue, COUNT(*) AS total_appointments
            FROM appointments
            GROUP BY stylist_id
        ) ap ON ap.stylist_id = st.stylist_id
        LEFT JOIN (
            SELECT a.stylist_id, ROUND(AVG(r.rating), 1) AS average_rating
            FROM reviews r
            INNER JOIN appointments a ON r.appointment_id = a.appointment_id
            GROUP BY a.stylist_id
        ) rv ON rv.stylist_id = st.stylist_id
                WHERE st.status IN ('Đang làm việc', 'available')
                    AND COALESCE(ap.total_revenue, 0) > 0
        ORDER BY total_revenue DESC, total_appointments DESC, average_rating DESC, u.full_name ASC
        LIMIT 3
    ";

    $stmt = $conn->query($sql);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $data
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>