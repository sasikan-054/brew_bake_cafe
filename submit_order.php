<?php
include "db_connect.php"; // เชื่อมต่อฐานข้อมูล

// รับค่าจาก JavaScript
$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$delivery_method = $_POST['delivery_method'];
$payment_method = $_POST['payment_method'];

// ตรวจสอบว่ามีสินค้าในตะกร้าหรือไม่
$cart_sql = "SELECT * FROM cart WHERE user_id = 1"; // สมมติ user_id = 1
$cart_result = $conn->query($cart_sql);

if ($cart_result->num_rows == 0) {
    echo json_encode(["success" => false, "message" => "ไม่มีสินค้าในตะกร้า"]);
    exit();
}

// บันทึกออเดอร์ลงตาราง orders
$total_amount = 0;
while ($row = $cart_result->fetch_assoc()) {
    $total_amount += $row['total_price'];
}

$order_sql = "INSERT INTO orders (user_id, order_date, total_amount, status, delivery_method, payment_method) 
              VALUES (1, NOW(), ?, 'pending', ?, ?)";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("dss", $total_amount, $delivery_method, $payment_method);
$order_stmt->execute();
$order_id = $order_stmt->insert_id; // ได้ค่า order_id ที่เพิ่งเพิ่ม

// บันทึกรายการสินค้าลง order_items
$cart_result->data_seek(0);
while ($row = $cart_result->fetch_assoc()) {
    $order_item_sql = "INSERT INTO order_items (order_id, product_id, drink_type_id, quantity, total_price) 
                       VALUES (?, ?, ?, ?, ?)";
    $order_item_stmt = $conn->prepare($order_item_sql);
    $order_item_stmt->bind_param("iiidd", $order_id, $row['product_id'], $row['drink_type_id'], $row['quantity'], $row['total_price']);
    $order_item_stmt->execute();
    $order_item_id = $order_item_stmt->insert_id;

    // บันทึก toppings ลง order_toppings
    $topping_sql = "SELECT topping_id FROM cart_toppings WHERE cart_id = ?";
    $topping_stmt = $conn->prepare($topping_sql);
    $topping_stmt->bind_param("i", $row['id']);
    $topping_stmt->execute();
    $topping_result = $topping_stmt->get_result();

    while ($topping_row = $topping_result->fetch_assoc()) {
        $order_topping_sql = "INSERT INTO order_toppings (order_item_id, topping_id) VALUES (?, ?)";
        $order_topping_stmt = $conn->prepare($order_topping_sql);
        $order_topping_stmt->bind_param("ii", $order_item_id, $topping_row['topping_id']);
        $order_topping_stmt->execute();
    }
}

// ลบสินค้าที่สั่งซื้อแล้วออกจากตะกร้า
$conn->query("DELETE FROM cart WHERE user_id = 1");

echo json_encode(["success" => true, "message" => "คำสั่งซื้อสำเร็จ!"]);
?>
