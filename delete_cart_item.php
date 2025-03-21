<?php
session_start();
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart_id = $_POST['cart_id'];

    // ลบสินค้าจากตระกร้า
    $delete_sql = "DELETE FROM cart WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $cart_id);
    $delete_stmt->execute();

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
