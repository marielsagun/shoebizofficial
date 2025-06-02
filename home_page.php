<?php
session_start();
include 'include/db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Home</title>
  <link rel="stylesheet" href="navbar/navbar.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
    }

    .container {
      max-width: 1500px;
      margin: 0 auto;
      padding: 30px 20px;
    }

    .title {
      font-size: 2rem;
      margin-bottom: 20px;
    }

    .filter-form {
      margin-bottom: 30px;
    }

    .products {
      display: flex;
      flex-wrap: wrap;
      margin-top: 25px;
      margin-bottom: 75px;
      justify-content: center;
      gap: 20px;
    }

    .card {
      width: 280px;
      background: #fdfdfd;
      border-radius: 10px;
      box-shadow: 0 6px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }

    .card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 8px 0;
    }

    .card h2 {
      font-size: 25px;
      margin: 5px 0;
    }

    .card .price {
      font-size: 18px;
      font-weight: 600;
      color: #379f56;
    }

    .card button {
      background-color: #34495e;
      color: white;
      width: 100%;
      margin-top: 25px;
      padding: 10px 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .card button:hover {
      transform: scale(1.05);
      background-color: #213345;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      overflow: auto;
    }

    .modal-content {
      background-color: #fffafa;
      border-radius: 12px;
      width: 90%;
      max-width: 800px;
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      padding: 0;
      z-index: 1000;
    }

    .modal-body {
      display: flex;
      flex-direction: row;
      padding: 20px;
      gap: 20px;
    }

    .modal-left img {
      width: 350px;
      height: 100%;
      object-fit: cover;
      border-radius: 10px;
    }

    .modal-right {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .modal-right h2 {
      margin-bottom: 3px;
      font-size: 35px;
    }

    .modal-right p {
      margin: 4px 0;
      font-size: 16px;
    }

    .modal-right .description {
      font-weight: 600;
    }

    .modal-right .price {
      font-size: 1.2rem;
      color: #379f56;
      margin: 10px 0;
      font-weight: bold;
    }

    .size-buttons {
      display: flex;
      gap: 8px;
      margin: 10px 0 20px;
    }

    .size-buttons input[type="radio"] {
      display: none;
    }

    .size-buttons label {
      padding: 8px 16px;
      border: 1px solid #ccc;
      border-radius: 8px;
      cursor: pointer;
      background-color: #f9f9f9;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .size-buttons input[type="radio"]:checked+label {
      background-color: #34495e;
      color: white;
      border-color: #2c3e50;
    }

    #modalQuantity {
      padding: 10px;
      width: 60px;
      font-size: 16px;
      text-align: center;
      border-radius: 6px;
      border: 1px solid #ccc;
      margin-bottom: 20px;
    }

    .modal-right button {
      background-color: #2c3e50;
      color: white;
      font-weight: 600;
      padding: 12px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .modal-right button:hover {
      background-color: #213345
    }

    .close {
      position: absolute;
      top: 12px;
      right: 20px;
      font-size: 28px;
      color: #999;
      cursor: pointer;
    }

    .close:hover {
      color: #213345
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

    .about-hero p {
      font-size: 1.2rem;
    }

    .footer {
      background: #213345;
      color: white;
      text-align: center;
      padding: 15px 20px;
    }

    .footer-logo img {
      width: 100px;
      margin-bottom: 10px;
    }

    .social-icons {
      margin-bottom: 15px;
    }

    .social-icons a {
      color: white;
      margin: 0 10px;
      font-size: 1.2rem;
      transition: color 0.3s;
    }

    .social-icons a:hover {
      color: #007bff;
    }

    .filter-form {
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 15px;
      justify-content: center;
    }

    .filter-form label {
      font-weight: bold;
      font-size: large;
      color: #2c3e50;
    }

    .filter-form select {
      padding: 8px 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 15px;
      cursor: pointer;
    }
  </style>
</head>

<body>

  <?php include 'navbar/navbar.php'; ?>

  <section class="about-hero">
    <h1>Browse our Products</h1>
    <p>Find your next favorite pair.</p>
  </section>

  <div class="container">
    <form method="GET" class="filter-form">
      <label for="brand">Filter by Brand:</label>
      <select name="brand" id="brand" onchange="this.form.submit()">
        <option value="">All Brands</option>
        <?php
      $brands = mysqli_query($conn, "SELECT DISTINCT brand_name FROM tbl_products ORDER BY brand_name ASC");
      while ($row = mysqli_fetch_assoc($brands)) {
          $selected = (isset($_GET['brand']) && $_GET['brand'] === $row['brand_name']) ? "selected" : "";
          echo '<option value="'.$row['brand_name'].'" '.$selected.'>'.$row['brand_name'].'</option>';
      }
      ?>
      </select>
    </form>

    <div class="products">
      <?php
    $filter = "";
    if (isset($_GET['brand']) && $_GET['brand'] !== "") {
        $brand = mysqli_real_escape_string($conn, $_GET['brand']);
        $filter = "WHERE brand_name = '$brand'";
    }

    $sql = "SELECT * FROM tbl_products $filter ORDER BY date_upload DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo '
            <div class="card">
                <img src="images/'.$row["image"].'" alt="'.$row["name"].'">
                <h2>'.htmlspecialchars($row["name"]).'</h2>
                <div class="price">₱'.number_format($row["price"], 2).'</div>
                <button onclick="openModal(
                  \''.htmlspecialchars($row["product_id"]).'\',
                  \''.htmlspecialchars($row["name"]).'\',
                  \''.htmlspecialchars($row["brand_name"]).'\',
                  \''.htmlspecialchars($row["description"]).'\',
                  \''.$row["stock_quantity"].'\',
                  \''.$row["price"].'\',
                  \''.$row["image"].'\'
                )">View Details</button>
            </div>';
        }
    } else {
        echo "<p>No products found for this brand.</p>";
    }

    mysqli_close($conn);
    ?>
    </div>
  </div>

  <footer class="footer">
    <div class="footer-logo">
      <img src="images/logo.png" alt="Shoe Biz Logo">
    </div>
    <div class="social-icons">
      <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
      <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
      <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
    </div>
    <p>For School Project only.</p>
  </footer>

  <div id="productModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <form method="POST" action="cart.php" class="modal-body">
        <div class="modal-left">
          <img id="modalImage" src="" alt="Product Image">
        </div>
        <div class="modal-right">
          <h2 id="modalName">Product Name</h2>
          <p class="description" id="modalDescription"></p>
          <p id="modalBrand"></p>
          <p id="modalStock"></p>
          <p class="price" id="modalPrice"></p>

          <input type="hidden" name="action" value="add">
          <input type="hidden" name="product_id" id="modalProductId">

          <label>Select Size</label>
          <div class="size-buttons">
            <?php for ($i = 35; $i <= 39; $i++): ?>
            <input type="radio" id="size<?= $i ?>" name="size" value="<?= $i ?>" required>
            <label for="size<?= $i ?>"><?= $i ?></label>
            <?php endfor; ?>
          </div>

          <label for="quantity">Quantity</label>
          <input type="number" name="quantity" id="modalQuantity" value="1" min="1" required>

          <?php if (isset($_SESSION['user_id'])): ?>
          <button type="submit" id="addToCartBtn" disabled>Add to Cart</button>
          <?php else: ?>
          <p style="color: #c53030;">Please <a href="login_page.php" style="color: #3182ce;">login</a> to add items to your cart.</p>
          <button type="button" disabled style="background-color: gray;">Add to Cart</button>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openModal(productId, name, brand, description, stock, price, image) {
      document.getElementById("modalProductId").value = productId;
      document.getElementById("modalName").innerText = name;
      document.getElementById("modalBrand").innerText = "Brand: " + brand;
      document.getElementById("modalDescription").innerText = description;
      document.getElementById("modalStock").innerText = "Available Stock: " + stock;
      document.getElementById("modalPrice").innerText = "₱" + parseFloat(price).toFixed(2);
      document.getElementById("modalImage").src = "images/" + image;
      document.getElementById("modalQuantity").max = stock;
      document.getElementById("productModal").style.display = "block";
    
      const radios = document.querySelectorAll('input[name="size"]');
      radios.forEach(r => r.checked = false);
      document.getElementById("addToCartBtn")?.setAttribute("disabled", "true");
    }
    
    function closeModal() {
      document.getElementById("productModal").style.display = "none";
    }
    
    window.onclick = function(event) {
      const modal = document.getElementById("productModal");
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
    
    document.querySelectorAll('input[name="size"]').forEach(radio => {
      radio.addEventListener("change", () => {
        const btn = document.getElementById("addToCartBtn");
        if (btn) btn.removeAttribute("disabled");
      });
    });
  </script>

</body>

</html>