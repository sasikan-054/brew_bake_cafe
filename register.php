<?php
session_start();
include 'db_connect.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone_number = $_POST['phone_number'];
    $date_of_birth = $_POST['date_of_birth'];
    $address = $_POST['address'];

    // ตรวจสอบว่ารหัสผ่านตรงกัน
    if ($password !== $confirm_password) {
        echo "<script>alert('รหัสผ่านไม่ตรงกัน!'); window.location='regis.html';</script>";
        exit();
    }

    // เข้ารหัสรหัสผ่าน
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // ตรวจสอบอีเมลซ้ำ
    $check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();
    if ($check_email->num_rows > 0) {
        echo "<script>alert('อีเมลนี้ถูกใช้งานแล้ว!'); window.location='regis.html';</script>";
        exit();
    }

    // บันทึกข้อมูลลงฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password, phone_number, date_of_birth, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $firstname, $lastname, $email, $hashed_password, $phone_number, $date_of_birth, $address);

    if ($stmt->execute()) {
        echo "<script>alert('ลงทะเบียนสำเร็จ!'); window.location='login.html';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด!'); window.location='regis.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
