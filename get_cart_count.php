<?php
include "db_connect.php";
session_start();

$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 1;

$sql = "SELECT SUM(quantity) AS count FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(["count" => $row["count"] ? $row["count"] : 0]);
?>