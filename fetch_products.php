<?php
include "db_connect.php";

$subcategory_ids = isset($_GET['subcategory_ids']) ? explode(',', $_GET['subcategory_ids']) : [];

if (count($subcategory_ids) > 0) {
    // สร้าง placeholders เช่น ?,?,? สำหรับ SQL
    $placeholders = implode(',', array_fill(0, count($subcategory_ids), '?'));

    // เตรียมคำสั่ง SQL
    $sql = "SELECT p.*, s.name AS subcategory_name 
            FROM products p
            JOIN subcategories s ON p.subcategory_id = s.id
            WHERE p.subcategory_id IN ($placeholders)";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing SQL: " . $conn->error);
    }

    // สร้างอาร์เรย์สำหรับ bind_param
    $types = str_repeat('i', count($subcategory_ids));
    $stmt->bind_param($types, ...array_map('intval', $subcategory_ids));
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $subcategory_name = $row['subcategory_name'];
        if (!isset($products[$subcategory_name])) {
            $products[$subcategory_name] = [];
        }
        $products[$subcategory_name][] = $row;
    }

    // Debug JSON Output
    header('Content-Type: application/json');
    echo json_encode($products, JSON_PRETTY_PRINT);
} else {
    header('Content-Type: application/json');
    echo json_encode(["error" => "No subcategory_ids provided"]);
}
?>
