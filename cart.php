<?php
session_start();
include 'include/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login_page.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = max(1, intval($_POST['quantity']));

    if ($_POST['action'] === 'add') {
        $check = $conn->prepare("SELECT * FROM tbl_cart WHERE user_id = ? AND product_id = ?");
        $check->bind_param("ii", $user_id, $product_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE tbl_cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO tbl_cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        }
        $stmt->execute();
    }

    if ($_POST['action'] === 'update') {
        $stmt = $conn->prepare("UPDATE tbl_cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        $stmt->execute();
    }

    if ($_POST['action'] === 'delete') {
        $stmt = $conn->prepare("DELETE FROM tbl_cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }

    header("Location: cart_page.php");
    exit();
}
?>