<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $subcategory_id = $_POST['subcategory_id'];
    $price = $_POST['price'];
    $image = '';

    // ตรวจสอบว่ามีไฟล์ถูกอัปโหลดหรือไม่
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $image = time() . "_" . basename($_FILES["image"]["name"]); // เปลี่ยนชื่อไฟล์ให้ไม่ซ้ำ
        $target_file = $target_dir . $image;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO products (name, subcategory_id, price, image) VALUES ('$name', '$subcategory_id', '$price', '$image')";
        } else {
            echo "<script>alert('อัปโหลดรูปภาพล้มเหลว!');</script>";
        }

    }

    // เพิ่มสินค้าเข้าไปในฐานข้อมูล
    $sql = "INSERT INTO products (name, subcategory_id, price, image) VALUES ('$name', '$subcategory_id', '$price', '$image')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('เพิ่มสินค้าสำเร็จ!'); window.location.href='admin_product.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ดึงหมวดหมู่สินค้าจากฐานข้อมูล
$categories_result = $conn->query("SELECT * FROM subcategories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มสินค้า</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

    <div class="w-full max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-6">เพิ่มสินค้าใหม่</h1>
        
        <form action="add_product.php" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block font-medium">ชื่อสินค้า:</label>
                <input type="text" name="name" required class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block font-medium">หมวดหมู่:</label>
                <select name="subcategory_id" required class="w-full border p-2 rounded">
                    <option value="">-- เลือกหมวดหมู่ --</option>
                    <?php while ($row = $categories_result->fetch_assoc()) : ?>
                        <option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-medium">ราคา (บาท):</label>
                <input type="number" name="price" step="0.01" required class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block font-medium">อัปโหลดรูปภาพ:</label>
                <input type="file" name="image" accept="image/*" class="w-full border p-2 rounded">
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">เพิ่มสินค้า</button>
        </form>
        
        <a href="admin_product.php" class="block text-center mt-4 text-gray-600 hover:und
