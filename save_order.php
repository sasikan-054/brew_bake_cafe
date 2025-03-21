<?php
session_start();
include 'db_connection.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

$user_id = $_SESSION['user_id']; // ดึง user_id จาก session
$data = json_decode(file_get_contents("php://input"), true);

if (!$user_id || !$data || count($data) === 0) {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit();
}

// 1️⃣ สร้าง Order ใหม่
$sql = "INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("id", $user_id, $data['totalAmount']);
$stmt->execute();
$order_id = $stmt->insert_id;

// 2️⃣ เพิ่มสินค้าไปที่ order_items
foreach ($data['selectedItems'] as $item) {
    $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
    $stmt->execute();
}

// 3️⃣ ลบสินค้าที่ถูกยืนยันออกจากตะกร้า
foreach ($data['selectedItems'] as $item) {
    $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $item['id']);
    $stmt->execute();
}

echo json_encode(["status" => "success", "message" => "Order placed successfully"]);
?>
