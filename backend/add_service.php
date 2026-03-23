<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['service_name']);
    $price = $_POST['service_price'];
    $duration = $_POST['service_duration'];
    $description = trim($_POST['service_description']);

    try {
        $sql = "INSERT INTO services (service_name, price, duration, description) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $price, $duration, $description]);

        echo "<script>
                alert('Thêm dịch vụ thành công!'); 
                window.location.href = '../frontend/html/html-admin/service-management.php';
              </script>";
    } catch(PDOException $e) {
        die("Lỗi hệ thống: " . $e->getMessage());
    }
}
?>