<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name    = $_POST['branch_name'];
    $address = $_POST['branch_address'];
    $phone   = $_POST['branch_phone'];
    $email   = $_POST['branch_email'];

    try {
        $sql = "INSERT INTO branches (branch_name, address, phone, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $address, $phone, $email]);

        echo "<script>
            alert('Thêm chi nhánh thành công!');
            window.location.href = '../frontend/html/html-admin/branch-management.php';
        </script>";
    } catch(PDOException $e) {
        die("Lỗi: " . $e->getMessage());
    }
}
?>