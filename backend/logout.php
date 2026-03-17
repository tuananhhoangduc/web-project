<?php
session_start(); // Gọi session ra

// Xóa sạch mọi thông tin trong Session (xé "thẻ chứng minh")
session_unset(); 
session_destroy(); 

// Đẩy người dùng về lại trang chủ
header("Location: ../frontend/html/html-client/index.php");
exit();
?>