<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @font-face {
            font-family: titleFont;
            src: url(css/Pricedown_Bl.otf);
        }

        * {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        body::-webkit-scrollbar {
            display: none;
        }

        body {
            background-color: #fdfdfd;
            color: #213345;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-wrapper {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
            display: flex;
            width: 90%;
            max-width: 900px;
            overflow: hidden;
        }

        .form-section {
            flex: 1;
            padding: 40px;
        }

        .logo-section {
            flex: 1;
            background-color: #2c3e50;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .logo-section img {
            max-width: 100%;
            max-height: 300px;
        }

        h2 {
            text-align: center;
            color: #34495e;
            margin-bottom: 20px;
            font-family: titleFont;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #2c3e50;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        input[type="checkbox"] {
            margin-top: 10px;
        }

        button[type="submit"] {
            background-color: #2c3e50;
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        button[type="submit"]:hover {
            background-color: #1a252f;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: #2c3e50;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .register-wrapper {
                flex-direction: column;
            }

            .logo-section {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="register-wrapper">
    <div class="form-section">
        <h2>Register</h2>
        <?php
        if (isset($_SESSION['success'])) {
            echo "<div style='color: green; font-weight: bold; text-align: center'>" . $_SESSION['success'] . "</div>";
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo "<div style='color: red; font-weight: bold; text-align: center'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }
        ?>
        <form action="register.php" method="POST" onsubmit="return validateForm()">
            <label for="firstname">First Name</label>
            <input type="text" name="firstname" id="firstname" required>

            <label for="lastname">Last Name</label>
            <input type="text" name="lastname" id="lastname" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <input type="checkbox" onclick="togglePassword()"> Show Password<br><br>

            <button type="submit">Register</button>
        </form>

        <div class="login-link">
            <p>Already have an account? <a href="login_page.php">Login here</a></p>
        </div>
    </div>

    <div class="logo-section">
        <img src="images/logo.png" alt="Logo">
    </div>
</div>

<script>
    function togglePassword() {
        const pass = document.getElementById("password");
        const confirm = document.getElementById("confirm_password");
        pass.type = pass.type === "password" ? "text" : "password";
        confirm.type = confirm.type === "password" ? "text" : "password";
    }

    function validateForm() {
        const pass = document.getElementById("password").value;
        const confirm = document.getElementById("confirm_password").value;
        if (pass !== confirm) {
            alert("Passwords do not match.");
            return false;
        }
        return true;
    }
</script>
</body>
</html>
