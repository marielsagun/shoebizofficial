<?php
include 'include/db.php';
session_start();

$action = $_POST['action'] ?? '';

function uploadImage($file) {
    $target_dir = "images/";
    $filename = basename($file["name"]);
    $target_file = $target_dir . time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $filename);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($file["tmp_name"]);

    if($check === false) {
        return false;
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return basename($target_file);
    }
    return false;
}

if ($action === 'add') {
    $name = $_POST['name'];
    $brand_name = $_POST['brand_name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $stock_quantity = intval($_POST['stock_quantity']);

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = uploadImage($_FILES['image']);
        if (!$imageName) {
            die("Image upload failed.");
        }
    } else {
        die("Image is required.");
    }

    $sql = "INSERT INTO tbl_products (name, brand_name, description, price, stock_quantity, image, date_upload) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdis", $name, $brand_name, $description, $price, $stock_quantity, $imageName);
    if ($stmt->execute()) {
        header("Location: admin_page.php");
        exit;
    } else {
        die("Insert failed: " . $conn->error);
    }
} 
else if ($action === 'edit') {
    $product_id = intval($_POST['product_id']);
    $name = $_POST['name'];
    $brand_name = $_POST['brand_name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $stock_quantity = intval($_POST['stock_quantity']);

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = uploadImage($_FILES['image']);
        if (!$imageName) {
            die("Image upload failed.");
        }

        $sql = "UPDATE tbl_products SET name=?, brand_name=?, description=?, price=?, stock_quantity=?, image=? WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdissi", $name, $brand_name, $description, $price, $stock_quantity, $imageName, $product_id);
    } else {

        $sql = "UPDATE tbl_products SET name=?, brand_name=?, description=?, price=?, stock_quantity=? WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdiii", $name, $brand_name, $description, $price, $stock_quantity, $product_id);
    }

    if ($stmt->execute()) {
        header("Location: admin_page.php");
        exit;
    } else {
        die("Update failed: " . $conn->error);
    }
}
else if ($action === 'delete') {
    $product_id = intval($_POST['product_id']);
    $sql = "DELETE FROM tbl_products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        header("Location: admin_page.php");
        exit;
    } else {
        die("Delete failed: " . $conn->error);
    }
}
else {
    die("Invalid action");
}
?>