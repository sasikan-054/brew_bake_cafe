<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // ลบข้อมูลสินค้า
    $sql = "DELETE FROM products WHERE id='$product_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('ลบสินค้าสำเร็จ!'); window.location.href='admin_product.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "<script>alert('ไม่พบสินค้าที่ต้องการลบ'); window.location.href='admin_product.php';</script>";
}

$conn->close();
?>
