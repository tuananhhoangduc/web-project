<?php
session_start();
require_once 'db_connect.php';

// Kiểm tra xem có phải dữ liệu được gửi từ Form POST sang không
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Hứng dữ liệu từ các ô input gửi sang
    $id      = $_POST['branch_id']; // Cái ID ẩn mà ta đã gài vào Form
    $name    = trim($_POST['branch_name']);
    $address = trim($_POST['branch_address']);
    $phone   = trim($_POST['branch_phone']);
    $email   = trim($_POST['branch_email']);

    // Kiểm tra xem ID có tồn tại không để tránh lỗi
    if (empty($id)) {
        die("Lỗi: Không tìm thấy ID chi nhánh cần cập nhật!");
    }

    try {
        // Lệnh SQL UPDATE để ghi đè dữ liệu mới vào DB
        $sql = "UPDATE branches SET branch_name = ?, address = ?, phone = ?, email = ? WHERE branch_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $address, $phone, $email, $id]);

        // Cập nhật xong thì báo thành công và load lại trang quản lý
        echo "<script>
                alert('Cập nhật chi nhánh thành công!'); 
                window.location.href = '../frontend/html/html-admin/branch-management.php';
              </script>";
    } catch(PDOException $e) {
        die("Lỗi hệ thống: " . $e->getMessage());
    }
} else {
    echo "Truy cập không hợp lệ!";
}
?>