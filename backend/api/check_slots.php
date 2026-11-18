<?php
require_once '../db_connect.php';
header('Content-Type: application/json');

if (isset($_GET['date']) && isset($_GET['stylist_id'])) {
    $date = $_GET['date'];
    $stylist_id = $_GET['stylist_id'];

    $sql = "SELECT appointment_time FROM appointments 
            WHERE stylist_id = ? AND appointment_date = ? AND status != 'cancelled'";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$stylist_id, $date]);
    $taken_slots = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $formatted_slots = array_map(function($time) {
        return substr($time, 0, 5);
    }, $taken_slots);

    echo json_encode(['status' => 'success', 'taken_slots' => $formatted_slots]);
}
?>