<?php
session_start();
include 'include/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login_page.html');
    exit();
}

$user_id = $_SESSION['user_id'];
$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Please upload a valid payment proof.";
    } else {
        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
        $file_type = $_FILES['payment_proof']['type'];
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Only JPG, PNG images or PDF files are allowed as payment proof.";
        }
    }

    $cart_query = $conn->prepare("
        SELECT tbl_cart.*, tbl_products.price 
        FROM tbl_cart 
        JOIN tbl_products ON tbl_cart.product_id = tbl_products.product_id 
        WHERE tbl_cart.user_id = ?
    ");
    $cart_query->bind_param("i", $user_id);
    $cart_query->execute();
    $cart_result = $cart_query->get_result();

    if ($cart_result->num_rows === 0) {
        $errors[] = "Your cart is empty.";
    }

    if (empty($errors)) {
        $total_amount = 0;
        $items = [];
        while ($row = $cart_result->fetch_assoc()) {
            $subtotal = $row['price'] * $row['quantity'];
            $total_amount += $subtotal;
            $items[] = [
                'product_id' => $row['product_id'],
                'quantity' => $row['quantity'],
                'price' => $row['price'],
            ];
        }

        $ext = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
        $filename = "payment_{$user_id}_" . time() . "." . $ext;
        $upload_dir = __DIR__ . '/uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $destination = $upload_dir . $filename;

        if (!move_uploaded_file($_FILES['payment_proof']['tmp_name'], $destination)) {
            $errors[] = "Failed to save the payment proof file.";
        }
    }

    if (empty($errors)) {
        $insert_order = $conn->prepare("
            INSERT INTO tbl_orders (user_id, order_date, total_amount, payment_proof) 
            VALUES (?, NOW(), ?, ?)
        ");
        $insert_order->bind_param("ids", $user_id, $total_amount, $filename);

        if ($insert_order->execute()) {
            $order_id = $insert_order->insert_id;

            foreach ($items as $item) {
                $update_stock = $conn->prepare("UPDATE tbl_products SET stock_quantity = stock_quantity - ? WHERE product_id = ?");
                $update_stock->bind_param("ii", $item['quantity'], $item['product_id']);
                $update_stock->execute();
            }

            $clear_cart = $conn->prepare("DELETE FROM tbl_cart WHERE user_id = ?");
            $clear_cart->bind_param("i", $user_id);
            $clear_cart->execute();

            $success = "Order placed successfully! Your order ID is #" . $order_id;
        } else {
            $errors[] = "Failed to save your order. Please try again.";
            if (file_exists($destination)) {
                unlink($destination);
            }
        }
    }
}

$cart_query = $conn->prepare("
    SELECT tbl_cart.*, tbl_products.name, tbl_products.price 
    FROM tbl_cart 
    JOIN tbl_products ON tbl_cart.product_id = tbl_products.product_id 
    WHERE tbl_cart.user_id = ?
");
$cart_query->bind_param("i", $user_id);
$cart_query->execute();
$cart_items = $cart_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Checkout</title>
  <style>
    @font-face {
      font-family: titleFont;
      src: url(css/Pricedown_Bl.otf);
    }

    * {
      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }

    h1 {
      margin-bottom: 10px;
      font-size: 2.5rem;
      font-family: titleFont;
    }

    body {
      background-color: #f4f7fa;
      color: #213345;
      margin: 0;
      padding: 0;
    }

    h1 {
      text-align: center;
      color: #2c3e50;
      margin: 40px 0 20px;
      font-size: 36px;
      letter-spacing: 1px;
    }

    .container {
      width: 90%;
      max-width: 1100px;
      margin: 0 auto;
    }

    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 3px 5px;
      background-color: transparent;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
      margin-top: 20px;
      font-size: 16px;
    }

    td {
      padding: 18px 22px;
      text-align: center;
      font-size: 16px;
      background-color: #fdfdfd ;
      padding: 10px 15px;
    }

    th {
      background-color: #2c3e50;
      color: #ffffff;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 14px;
      padding: 20px;
      border-radius: 0 0 8px 8px;
    }

    tr:hover td {
      background-color: #f5f8fa;
    }

    .total-row td {
      font-weight: bold;
      font-size: 16px;
      background-color: #f1f3f5;
    }

    form {
      background-color: #fdfdfd ;
      margin: 30px auto;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
      width: 100%;
      max-width: 600px;
    }

    label {
      display: block;
      font-weight: 600;
      color: #2c3e50;
      margin-bottom: 12px;
      font-size: 15px;
    }

    input[type="file"] {
      width: 100%;
      padding: 14px;
      font-size: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      background-color: #fafafa;
      margin-bottom: 20px;
      cursor: pointer;
      transition: border 0.3s ease;
    }

    input[type="file"]:hover {
      border-color: #2c3e50;
    }

    input[type="submit"] {
      background-color: #34495e;
      color: white;
      border: none;
      padding: 14px 32px;
      font-size: 16px;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      display: block;
      margin: 0 auto;
    }

    input[type="submit"]:hover {
      background-color: #213345;
    }

    .message {
      max-width: 600px;
      margin: 20px auto;
      padding: 16px 20px;
      border-radius: 10px;
      font-size: 14px;
      text-align: center;
    }

    .success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .error {
      background-color: #ffe6e6;
      color: #b00020;
      border: 1px solid #ffb3b3;
    }

    p.empty-cart {
      text-align: center;
      font-size: 18px;
      color: #7f8c8d;
      margin-top: 50px;
    }

    p.empty-cart a {
      color: #2c3e50;
      text-decoration: none;
      font-weight: bold;
    }

    p.empty-cart a:hover {
      text-decoration: underline;
    }
  </style>


</head>

<body>
  <h1>Checkout</h1>

  <div class="container">
    <?php if (!empty($errors)): ?>
    <div class="message error">
      <?php foreach ($errors as $err) echo htmlspecialchars($err) . "<br>"; ?>
    </div>
    <?php endif; ?>

    <?php if ($success): ?>
    <div class="message success"><?= htmlspecialchars($success) ?></div>
    echo "<script>localStorage.setItem('refresh_cart', 'true');</script>";
    <?php else: ?>

    <?php if ($cart_items->num_rows === 0): ?>
    <p style="text-align:center; font-size:18px; color:#7f8c8d;">Your cart is empty. <a href="home_page.php">Start shopping now.</a></p>
    <?php else: ?>

    <table>
      <thead>
        <tr>
          <th>Product</th>
          <th>Unit Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php
                $grand_total = 0;
                while ($item = $cart_items->fetch_assoc()):
                    $subtotal = $item['price'] * $item['quantity'];
                    $grand_total += $subtotal;
                ?>
        <tr>
          <td><?= htmlspecialchars($item['name']) ?></td>
          <td>₱<?= number_format($item['price'], 2) ?></td>
          <td><?= $item['quantity'] ?></td>
          <td>₱<?= number_format($subtotal, 2) ?></td>
        </tr>
        <?php endwhile; ?>
        <tr class="total-row">
          <td colspan="3" style="text-align:right;">Grand Total:</td>
          <td>₱<?= number_format($grand_total, 2) ?></td>
        </tr>
      </tbody>
    </table>

    <form method="POST" enctype="multipart/form-data" novalidate>
      <label for="payment_proof">Upload Payment Proof (JPG, PNG, or PDF):</label>
      <input type="file" name="payment_proof" id="payment_proof" required accept=".jpg,.jpeg,.png,.pdf" />

      <input type="submit" value="Place Order" />
    </form>

    <?php endif; ?>

    <?php endif; ?>
  </div>
</body>

</html>