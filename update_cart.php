<?php
session_start();
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];

    // คำนวณราคาทั้งหมดใหม่
    $sql = "SELECT p.price FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $product_price = $row['price'];
    $total_price = $product_price * $quantity;

    // อัปเดตจำนวนสินค้าและราคาทั้งหมดในตระกร้า
    $update_sql = "UPDATE cart SET quantity = ?, total_price = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("idi", $quantity, $total_price, $cart_id);
    $update_stmt->execute();

    // คำนวณยอดรวมของตระกร้า
    $total_sql = "SELECT SUM(total_price) AS total_amount FROM cart WHERE user_id = ?";
    $total_stmt = $conn->prepare($total_sql);
    $total_stmt->bind_param("i", $_SESSION['user_id']);
    $total_stmt->execute();
    $total_result = $total_stmt->get_result();
    $total_row = $total_result->fetch_assoc();

    echo json_encode(['totalAmount' => number_format($total_row['total_amount'], 2)]);
}
?>
