<?php
header('Content-Type: application/json; charset=utf-8');
echo json_encode(["status" => "success", "message" => "Đã đăng xuất. Vui lòng xóa Token ở máy khách!"]);
?>