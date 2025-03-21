<?php
include "db_connect.php"; // เชื่อมต่อฐานข้อมูล
session_start();

$user_id = $_SESSION['user_id']; // ดึง user_id จาก session
$cart_item_id = $_POST['cart_item_id']; // รับค่า cart item ID
$change = intval($_POST['change']); // รับค่าการเปลี่ยนแปลง (เพิ่มหรือลด)

// ตรวจสอบว่าจำนวนสินค้าจะไม่ต่ำกว่า 1
$query = "UPDATE cart SET quantity = GREATEST(quantity + $change, 1) WHERE id = '$cart_item_id' AND user_id = '$user_id'";
$result = mysqli_query($conn, $query);

if ($result) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Update failed"]);
}
?>
