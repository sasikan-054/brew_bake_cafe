<?php
session_start();
include 'db_connection.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT id, total_amount, status FROM orders WHERE user_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode($orders);
?>
