<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id    = $_POST['customer_id'];
    $name  = trim($_POST['customer_name']);
    $phone = trim($_POST['customer_phone']);
    $email = trim($_POST['customer_email']);

    try {
        $sql = "UPDATE users SET full_name = ?, phone = ?, email = ? WHERE user_id = ? AND role = 'customer'";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $phone, $email, $id]);

        echo "<script>alert('Cập nhật thành công!'); window.location.href = '../frontend/html/html-admin/customer-management.php';</script>";
    } catch(PDOException $e) {
        die("Lỗi cập nhật: " . $e->getMessage());
    }
}
?>