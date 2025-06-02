<?php
include 'db_connection.php';

$brand_name = isset($_GET['brand_name']) ? $_GET['brand_name'] : '';

$sql = "SELECT * FROM tbl_products";
if (!empty($brand_name)) {
    $sql .= " WHERE brand_name = ?";
}

$stmt = $conn->prepare($sql);
if (!empty($brand_name)) {
    $stmt->bind_param("s", $brand_name);
}
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);
?>