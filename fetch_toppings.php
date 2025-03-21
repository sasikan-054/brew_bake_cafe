<?php
include 'db_connect.php';

$product_id = $_GET['product_id'];

$queryDrinkTypes = "SELECT * FROM drink_types";
$queryToppings = "SELECT * FROM toppings";
$resultDrinkTypes = $conn->query($queryDrinkTypes);
$resultToppings = $conn->query($queryToppings);

$drinkTypes = [];
$toppings = [];

while ($row = $resultDrinkTypes->fetch_assoc()) {
    $drinkTypes[] = $row;
}

while ($row = $resultToppings->fetch_assoc()) {
    $toppings[] = $row;
}

echo json_encode([
    'drinkTypes' => $drinkTypes,
    'toppings' => $toppings
]);
?>
