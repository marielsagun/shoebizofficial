<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log In Account</title>
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
      display: flex;
      height: 100vh;
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

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
    }

    button {
      background-color: #2c3e50;
      color: white;
      border: none;
      width: 100%;
      padding: 12px;
      margin-top: 20px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #1a252f;
    }

    .register-link {
      text-align: center;
      margin-top: 20px;
    }

    .register-link a {
      color: #2c3e50;
      text-decoration: none;
      font-weight: 600;
    }

    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>

  <div class="register-wrapper">

    <div class="logo-section">
      <img src="images/logo.png" alt="Logo">
    </div>

    <div class="form-section">
      <h2>Log In your Account</h2>
      <form action="login.php" method="POST">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Login</button>
      </form>
      <div class="register-link">
        <p>Don't have an account? <a href="register_page.php">Register here</a></p>
      </div>
    </div>
  </div>

</body>

</html>