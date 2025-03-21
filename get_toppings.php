<?php
include "db_connect.php"; // เชื่อมต่อฐานข้อมูล

$query = "SELECT id, name, price FROM toppings";
$result = mysqli_query($conn, $query);

$toppings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $toppings[] = $row;
}

echo json_encode($toppings);
?>
