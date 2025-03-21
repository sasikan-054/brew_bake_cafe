<?php
include "db_connect.php"; // เชื่อมต่อฐานข้อมูล
session_start();

$user_id = $_SESSION['user_id']; // ดึง user_id จาก session

$query = "
    SELECT 
        cart.id AS cart_id,
        products.id AS product_id,
        products.name,
        products.image,
        products.price,
        drink_types.name AS drink_type,
        cart.quantity,
        GROUP_CONCAT(toppings.name SEPARATOR ', ') AS toppings
    FROM cart
    JOIN products ON cart.product_id = products.id
    JOIN drink_types ON cart.drink_type_id = drink_types.id
    LEFT JOIN cart_toppings ON cart.id = cart_toppings.cart_id
    LEFT JOIN toppings ON cart_toppings.topping_id = toppings.id
    WHERE cart.user_id = '$user_id'
    GROUP BY cart.id
";

$result = mysqli_query($conn, $query);

$cart_items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cart_items[] = $row;
}

echo json_encode($cart_items);
?>
