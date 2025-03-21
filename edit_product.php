<?php
include 'db_connect.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('ไม่พบสินค้าที่ต้องการแก้ไข'); window.location.href='admin_product.php';</script>";
    exit();
}

$product_id = $_GET['id'];

// ดึงข้อมูลสินค้าที่ต้องการแก้ไข
$result = $conn->query("SELECT * FROM products WHERE id = '$product_id'");
$product = $result->fetch_assoc();

if (!$product) {
    echo "<script>alert('ไม่พบสินค้าที่ต้องการแก้ไข'); window.location.href='admin_product.php';</script>";
    exit();
}

// ดึงหมวดหมู่สินค้า
$categories_result = $conn->query("SELECT * FROM subcategories");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $subcategory_id = $_POST['subcategory_id'];
    $price = $_POST['price'];
    $image = $product['image']; // ใช้รูปเดิมหากไม่มีการอัปโหลดใหม่

    // ตรวจสอบว่ามีไฟล์อัปโหลดหรือไม่
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    // อัปเดตข้อมูลสินค้า
    $sql = "UPDATE products SET name='$name', subcategory_id='$subcategory_id', price='$price', image='$image' WHERE id='$product_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('อัปเดตสินค้าสำเร็จ!'); window.location.href='admin_product.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขสินค้า</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

    <div class="w-full max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-6">แก้ไขสินค้า</h1>
        
        <form action="edit_product.php?id=<?= $product_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block font-medium">ชื่อสินค้า:</label>
                <input type="text" name="name" value="<?= $product['name']; ?>" required class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block font-medium">หมวดหมู่:</label>
                <select name="subcategory_id" required class="w-full border p-2 rounded">
                    <?php while ($row = $categories_result->fetch_assoc()) : ?>
                        <option value="<?= $row['id']; ?>" <?= $row['id'] == $product['subcategory_id'] ? 'selected' : ''; ?>>
                            <?= $row['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-medium">ราคา (บาท):</label>
                <input type="number" name="price" value="<?= $product['price']; ?>" step="0.01" required class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block font-medium">อัปโหลดรูปภาพใหม่ (ถ้ามี):</label>
                <input type="file" name="image" accept="image/*" class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <img src="uploads/<?= $product['image']; ?>" class="h-20">
            </div>

            <button type="submit" class="w-full bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600">อัปเดตสินค้า</button>
        </form>

        <a href="admin_product.php" class="block text-center mt-4 text-gray-600 hover:underline">กลับไปหน้าจัดการสินค้า</a>
    </div>

</body>
</html>

<?php
$conn->close();
?>
