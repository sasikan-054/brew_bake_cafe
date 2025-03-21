<?php
include "db_connect.php";
session_start();

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 1; 
    $product_id = isset($_POST["product_id"]) ? intval($_POST["product_id"]) : 0;
    $drink_type_id = isset($_POST["drink_type_id"]) ? intval($_POST["drink_type_id"]) : 1;
    $toppings = isset($_POST["toppings"]) ? explode(",", $_POST["toppings"]) : [];
    $quantity = 1;

    if ($product_id == 0) {
        echo json_encode(["success" => false, "message" => "Invalid product ID"]);
        exit;
    }

    // เพิ่มสินค้าในตะกร้า
    $insert_sql = "INSERT INTO cart (user_id, product_id, drink_type_id, quantity, total_price) 
                   VALUES (?, ?, ?, ?, (SELECT price FROM products WHERE id = ?) * ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("iiiiii", $user_id, $product_id, $drink_type_id, $quantity, $product_id, $quantity);
    $stmt->execute();
    $cart_id = $conn->insert_id;

    // เพิ่ม Toppings
    foreach ($toppings as $topping_id) {
        $insert_topping_sql = "INSERT INTO cart_toppings (cart_id, topping_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_topping_sql);
        $stmt->bind_param("ii", $cart_id, $topping_id);
        $stmt->execute();
    }

    echo json_encode(["success" => true, "message" => "Added to cart"]);
    exit;
}
?>