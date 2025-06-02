<?php
session_start(); 
include("include/db.php");

$fname = $_POST['firstname'];
$lname = $_POST['lastname'];
$email = $_POST['email'];
$pass = $_POST['password'];

$check = $conn->query("SELECT * FROM tbl_users WHERE email = '$email'");
if ($check->num_rows > 0) {
    $_SESSION['error'] = "Email is already registered.";
    header("Location: register_page.php");
    exit();
}

$hashed = password_hash($pass, PASSWORD_DEFAULT);

$sql = "INSERT INTO tbl_users (first_name, last_name, email, password) 
        VALUES ('$fname', '$lname', '$email', '$hashed')";

if ($conn->query($sql) === TRUE) {
    $_SESSION['success'] = "Registered successfully. Please log in.";
    header("Location: register_page.php");
    exit();
} else {
    $_SESSION['error'] = "Error: " . $conn->error;
    header("Location: register_page.php");
    exit();
}

?>
