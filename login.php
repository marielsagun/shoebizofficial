<?php
session_start();
include("include/db.php");


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'], $_POST['password'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {

            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['first_name'] = $row['first_name'];

            if ($row['role'] === 'admin') {
                header("Location: admin_page.php");
            } else {
                header("Location: home_page.php");
            }
            exit();
        } else {
            echo "<script>alert('Incorrect password.'); window.location.href='login_page.php';</script>";
        }
    } else {
        echo "<script>alert('No account found with that email.'); window.location.href='login_page.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Please enter both email and password.'); window.location.href='login_page.php';</script>";
}
?>