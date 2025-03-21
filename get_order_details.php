<?php
include "db_connect.php"; // เชื่อมต่อฐานข้อมูล

$order_id = $_GET["order_id"]; // รับค่า order_id จาก URL

$query = "SELECT oi.id as order_item_id, p.name AS product_name, dt.name AS drink_type, oi.quantity, oi.price
          FROM order_items oi
          JOIN products p ON oi.product_id = p.id
          JOIN drink_types dt ON oi.drink_type_id = dt.id
          WHERE oi.order_id = '$order_id'";

$result = mysqli_query($conn, $query);

$order_items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $order_item_id = $row["order_item_id"];
    $row["toppings"] = [];

    // ดึงท็อปปิ้งของสินค้านั้น
    $topping_query = "SELECT t.name FROM order_toppings ot 
                      JOIN toppings t ON ot.topping_id = t.id 
                      WHERE ot.order_item_id = '$order_item_id'";
    $topping_result = mysqli_query($conn, $topping_query);
    while ($topping = mysqli_fetch_assoc($topping_result)) {
        $row["toppings"][] = $topping["name"];
    }

    $order_items[] = $row;
}

echo json_encode($order_items);
?>
