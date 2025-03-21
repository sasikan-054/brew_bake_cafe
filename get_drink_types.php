<?php
include "db_connect.php"; // เชื่อมต่อฐานข้อมูล

$query = "SELECT id, name, price FROM drink_types";
$result = mysqli_query($conn, $query);

$drinkTypes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $drinkTypes[] = $row;
}

echo json_encode($drinkTypes);
?>
