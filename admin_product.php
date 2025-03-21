<?php
// เชื่อมต่อฐานข้อมูล
include 'db_connect.php';

// ดึงข้อมูลสินค้าจากฐานข้อมูล
$sql = "SELECT p.id, p.name, p.image, p.price, s.name AS subcategory 
        FROM products p
        LEFT JOIN subcategories s ON p.subcategory_id = s.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Mitr:wght@200;300;400;500;600;700&display=swap');
    </style>
</head>
<body class="bg-white font-[Roboto]">

    <div class="w-screen h-auto bg-no-repeat bg-cover bg-top" style="background-image: url('images/backG.png');">
        <nav class="flex justify-between items-center px-10 py-3 shadow-lg">
            <div class="flex items-center space-x-2">
                <div class="w-12">
                    <img src="images/logo.png" alt="Logo">
                </div>
                <div class="text-lg font-medium font-Pacifico">
                    Brew & Bake <span class="text-orange-500">Cafe</span>
                </div>
            </div>

            <div class="flex justify-center flex-1 space-x-6 font-medium">
                <a href="home.html" class="hover:text-orange-500">Home</a>
                
                <a href="#" class="hover:text-orange-500">Product</a>
                    
            </div>
        
            <div class="flex items-center space-x-4">
                <a href="login.html"> 
                    <button class="p-2 bg-blue-400 text-white rounded-full hover:bg-orange-600">
                        <img src="images/avatar.png" alt="Login" class="w-5 h-5">
                    </button>
                </a>
            </div>
        </nav>
    </div>

    <section class="container mx-auto py-20 flex flex-row items-center justify-center">
        <div class="w-4/5 mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4">จัดการสินค้า</h1>
            <a href="add_product.php" class="bg-blue-500 text-white px-4 py-2 rounded">เพิ่มสินค้า</a>
            <table class="w-full mt-4 border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">รูปภาพ</th>
                        <th class="border p-2">ชื่อสินค้า</th>
                        <th class="border p-2">หมวดหมู่</th>
                        <th class="border p-2">ราคา</th>
                        <th class="border p-2">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr class="border">
                        <td class="border p-2"><img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" class="h-16"></td>
                        <td class="border p-2"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td class="border p-2"><?php echo htmlspecialchars($row['subcategory']); ?></td>
                        <td class="border p-2"><?php echo number_format($row['price'], 2); ?> ฿</td>
                        <td class="border p-2">
                            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="bg-yellow-500 text-white px-3 py-1 rounded">แก้ไข</a>
                            <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="bg-red-500 text-white px-3 py-1 rounded" onclick="return confirm('คุณแน่ใจหรือไม่?');">ลบ</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
