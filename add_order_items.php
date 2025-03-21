<?php
include "db_connect.php"; // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST["order_id"]; // รับค่า order_id ที่สร้างแล้ว
    $cart_items = json_decode($_POST["cart_items"], true); // รับข้อมูลจากตะกร้า

    foreach ($cart_items as $item) {
        $product_id = $item["product_id"];
        $drink_type_id = $item["drink_type_id"];
        $quantity = $item["quantity"];
        $price = $item["price"];
        $toppings = $item["toppings"]; // อาเรย์ของ topping_id

        // บันทึกลงตาราง order_items
        $query = "INSERT INTO order_items (order_id, product_id, drink_type_id, quantity, price)
                  VALUES ('$order_id', '$product_id', '$drink_type_id', '$quantity', '$price')";
        if (mysqli_query($conn, $query)) {
            $order_item_id = mysqli_insert_id($conn); // ดึง ID ของสินค้าที่เพิ่งเพิ่ม

            // บันทึกข้อมูลท็อปปิ้งถ้ามี
            foreach ($toppings as $topping_id) {
                $query_topping = "INSERT INTO order_toppings (order_item_id, topping_id) VALUES ('$order_item_id', '$topping_id')";
                mysqli_query($conn, $query_topping);
            }
        }
    }

    echo json_encode(["success" => true, "message" => "บันทึกคำสั่งซื้อสำเร็จ"]);
} else {
    echo json_encode(["success" => false, "message" => "ไม่สามารถบันทึกข้อมูลได้"]);
}
?>
