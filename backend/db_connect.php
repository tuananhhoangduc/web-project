<?php
$host = "sql312.infinityfree.com";
$db_name = "if0_43473955_barber_shop_db";
$username = "if0_43473955";
$password = "thhosting2004";

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$db_name;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Kết nối thất bại. Kiểm tra lại thông tin database trên hosting.");
}
?>