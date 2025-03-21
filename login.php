<?php
session_start();
include 'db_connect.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
    $stmt = $conn->prepare("SELECT id, firstname, lastname, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $firstname, $lastname, $email, $hashed_password, $role);
    
    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        
        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
            
            // เช็ค role เพื่อพาไปหน้า admin หรือ customer
            if ($role == 'admin') {
                echo "<script>alert('ยินดีต้อนรับ Admin!'); window.location='home.html';</script>";
            } else {
                echo "<script>alert('เข้าสู่ระบบสำเร็จ!'); window.location='index.html';</script>";
            }
        } else {
            echo "<script>alert('รหัสผ่านไม่ถูกต้อง!'); window.location='login.html';</script>";
        }
    } else {
        echo "<script>alert('ไม่พบบัญชีนี้!'); window.location='login.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
