<?php
session_start();
include 'include/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login_page.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$query = $conn->prepare("
    SELECT tbl_cart.*, tbl_products.name, tbl_products.price, tbl_products.stock_quantity
    FROM tbl_cart
    JOIN tbl_products ON tbl_cart.product_id = tbl_products.product_id
    WHERE tbl_cart.user_id = ?
");
$query->bind_param("i", $user_id);
$query->execute();
$cart_items = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Your Cart</title>
  <style>
    @font-face {
      font-family: titleFont;
      src: url(css/Pricedown_Bl.otf);
    }

    * {
      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }

    h1,
    h2 {
      font-family: titleFont;
    }

    body::-webkit-scrollbar {
      display: none;
    }

    body {
      background-color: #f2f3f4;
      color: #213345;
      margin: 0;
      padding: 0;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    body::-webkit-scrollbar {
      display: none;
    }

    .about-hero {
      background-color: #2c3e50;
      color: white;
      text-align: center;
      padding: 60px 20px;
    }

    .about-hero h1 {
      margin-bottom: 10px;
      font-size: 2.5rem;
    }

    table {
      width: 88%;
      margin-bottom: 40px;
      border-collapse: separate;
      border-spacing: 3px 5px;
      background-color: transparent;
      justify-self: center;
      font-size: 16px;
    }

    th {
      background-color: #2c3e50;
      border-radius: 0 0 8px 8px;
      color: #fffafa;
      padding: 20px;
    }

    td {
      background-color: #fdfdfd ;
      padding: 10px 15px;
      text-align: center;
      border-bottom: 1px solid #ccc;
      border-radius: 0 0 8px 8px;
    }

    tr {
      box-shadow: 0 10px 10px rgba(0, 0, 0, 0.05);
      border-radius: 8px;
    }

    .qty-control {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
    }

    .qty-control button {
      width: 25px;
      height: 25px;
      border-radius: 6px;
      border: none;
      background-color: #34495e;
      color: white;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .qty-control button:hover {
      background-color: #213345;
    }

    .qty-control input {
      width: 50px;
      height: 30px;
      font-weight: bold;
      text-align: center;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 4px;
      font-size: 14px;
    }

    button.update-btn,
    button.remove-btn {
      padding: 10px 16px;
      border: none;
      border-radius: 6px;
      background-color: #34495e;
      color: white;
      font-weight: 500;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin: 4px 0;
    }

    button.update-btn:hover,
    button.remove-btn:hover {
      background-color: #213345;
    }

    .actions {
      text-align: center;
      margin: 40px 0;
    }

    .btn {
      display: inline-block;
      font-size: 16px;
      font-weight: 600;
      padding: 15px 30px;
      margin: 0 10px;
      border: none;
      border-radius: 6px;
      color: white;
      background-color: #34495e;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .btn:hover {
      background-color: #213345;
      transform: translateY(-2px);
    }

    .grand-total {
      font-weight: bold;
      font-size: 18px;
      color: #213345;
    }

    .empty-cart {
      text-align: center;
      padding: 40px;
      font-size: 20px;
      color: #7f8c8d;
    }
  </style>
</head>

<body>

  <section class="about-hero">
    <h1>Your Shopping Cart</h1>
  </section>
  <br>
  <br>

  <table>
    <tr>
      <th>Product</th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Subtotal</th>
      <th>Actions</th>
    </tr>

    <?php
    $grand_total = 0;
    if ($cart_items->num_rows > 0):
        while ($item = $cart_items->fetch_assoc()):
            $subtotal = $item['price'] * $item['quantity'];
            $grand_total += $subtotal;
    ?>
    <tr data-product-id="<?= $item['product_id'] ?>" data-max="<?= $item['stock_quantity'] ?>">
      <td><?= htmlspecialchars($item['name']) ?></td>
      <td>₱<?= number_format($item['price'], 2) ?></td>
      <td>
        <div class="qty-control">
          <button class="decrease">−</button>
          <input type="number" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock_quantity'] ?>" class="qty-input" />
          <button class="increase">+</button>
        </div>
      </td>
      <td class="subtotal">₱<?= number_format($subtotal, 2) ?></td>
      <td>
        <form method="POST" action="cart.php" style="display:inline;">
          <input type="hidden" name="action" value="update">
          <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
          <input type="hidden" name="quantity" class="hidden-qty" value="<?= $item['quantity'] ?>">
          <button type="submit" class="update-btn">Update</button>
        </form>
        <form method="POST" action="cart.php" style="display:inline;">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
          <button type="submit" class="remove-btn" onclick="return confirm('Remove this item?')">Remove</button>
        </form>
      </td>
    </tr>
    <?php endwhile; ?>
    <tr>
      <td colspan="4" style="text-align:right;" class="grand-total">Grand Total:</td>
      <td colspan="2" class="grand-total" id="grand-total">₱<?= number_format($grand_total, 2) ?></td>
    </tr>
    <?php else: ?>
    <tr>
      <td colspan="5" class="empty-cart">Your cart is currently empty.</td>
    </tr>
    <?php endif; ?>
  </table>

  <div class="actions">
    <a href="home_page.php" class="btn">← Continue Shopping</a>
    <?php if ($grand_total > 0): ?>
    <button class="btn" onclick="openCheckoutModal()">Proceed to Checkout →</button>
    <?php endif; ?>
  </div>

  <div id="checkoutModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center;">
    <div style="width:90%; max-width:800px; height:90%; background:#fff; border-radius:10px; overflow:hidden; position:relative;">
      <button onclick="closeCheckoutModal()" style="position:absolute; top:10px; right:10px; background:#e74c3c; color:#fff; border:none; border-radius:4px; padding:6px 12px; cursor:pointer;">Close</button>
      <iframe src="checkout_page.php" style="width:100%; height:100%; border:none;"></iframe>
    </div>
  </div>

  <script>
    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(el => {
            total += parseFloat(el.textContent.replace('₱', '').replace(/,/g, ''));
        });
        document.getElementById('grand-total').textContent = '₱' + total.toLocaleString(undefined, {minimumFractionDigits: 2});
    }
    
    document.querySelectorAll('tr[data-product-id]').forEach(row => {
        const price = parseFloat(row.children[1].textContent.replace('₱', '').replace(/,/g, ''));
        const maxQty = parseInt(row.getAttribute('data-max')) || Infinity;
    
        const input = row.querySelector('.qty-input');
        const hiddenQty = row.querySelector('.hidden-qty');
        const subtotalEl = row.querySelector('.subtotal');
        const increaseBtn = row.querySelector('.increase');
        const decreaseBtn = row.querySelector('.decrease');
        const updateBtn = row.querySelector('.update-btn');
    
        const updateSubtotal = () => {
            let qty = parseInt(input.value);
            if (isNaN(qty) || qty < 1) qty = 1;
            if (qty > maxQty) {
                qty = maxQty;
                alert(`Maximum quantity available is ${maxQty}`);
            }
            input.value = qty;
            hiddenQty.value = qty;
    
            const subtotal = price * qty;
            subtotalEl.textContent = '₱' + subtotal.toLocaleString(undefined, {minimumFractionDigits: 2});
            flashSubtotal(subtotalEl);
            updateGrandTotal();
        };
    
        increaseBtn.addEventListener('click', () => {
            let current = parseInt(input.value);
            if (current < maxQty) {
                input.value = current + 1;
                updateSubtotal();
            } else {
                alert(`Maximum quantity available is ${maxQty}`);
            }
        });
    
        decreaseBtn.addEventListener('click', () => {
            let current = parseInt(input.value);
            if (current > 1) {
                input.value = current - 1;
                updateSubtotal();
            }
        });
    
        input.addEventListener('input', () => {
            let val = parseInt(input.value);
            if (isNaN(val) || val < 1) {
                input.value = 1;
            } else if (val > maxQty) {
                input.value = maxQty;
                alert(`Maximum quantity available is ${maxQty}`);
            }
            updateSubtotal();
        });
    });

    function openCheckoutModal() {
      document.getElementById('checkoutModal').style.display = 'flex';
    }

    function closeCheckoutModal() {
      document.getElementById('checkoutModal').style.display = 'none';
    }

    window.addEventListener("storage", function(event) {
        if (event.key === "refresh_cart" && event.newValue === "true") {

            localStorage.setItem("refresh_cart", "false");
            
            setTimeout(function() {
                location.reload();
            }, 2000);
        }
    });

  </script>

</body>

</html>