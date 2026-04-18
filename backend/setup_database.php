<?php
require_once 'db_connect.php';

function createReviewsTable() {
    global $conn;
    
    try {
        // Kiểm tra bảng đã tồn tại chưa
        $stmt = $conn->query("SHOW TABLES LIKE 'reviews'");
        if ($stmt->rowCount() > 0) {
            return ['status' => 'info', 'message' => 'Bảng reviews đã tồn tại'];
        }

        $sql = "CREATE TABLE reviews (
            review_id INT PRIMARY KEY AUTO_INCREMENT,
            appointment_id INT NOT NULL UNIQUE,
            user_id INT NOT NULL,
            stylist_id INT NOT NULL,
            rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
            comment TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (appointment_id) REFERENCES appointments(appointment_id),
            FOREIGN KEY (user_id) REFERENCES users(user_id),
            FOREIGN KEY (stylist_id) REFERENCES stylists(stylist_id)
        )";
        
        $conn->exec($sql);
        return ['status' => 'success', 'message' => 'Bảng reviews đã được tạo thành công!'];
    } catch(PDOException $e) {
        return ['status' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()];
    }
}

// Chạy setup khi truy cập
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'setup') {
    echo json_encode(createReviewsTable());
} else {
    echo '<form method="POST" style="padding: 20px;">
            <h2>Setup Database</h2>
            <button type="submit" name="action" value="setup" class="btn" style="padding: 10px 20px; font-size: 16px;">
                ✅ Tạo Bảng Reviews
            </button>
          </form>';
}
?>