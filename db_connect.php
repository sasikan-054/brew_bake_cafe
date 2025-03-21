<?php
$servername = "localhost";  // หรือ 127.0.0.1
$username = "root"; // ชื่อผู้ใช้ MySQL
$password = ""; // รหัสผ่าน MySQL (หากมีให้ใส่)
$database = "brew_bake_cafe"; // ชื่อฐานข้อมูล

$conn = new mysqli($servername, $username, $password, $database);

// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
