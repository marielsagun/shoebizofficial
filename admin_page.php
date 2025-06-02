<?php
session_start();
include 'include/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_page.php");
    exit();
}

$filter_brand = $_GET['filter_brand'] ?? '';

$query = "SELECT * FROM tbl_products";
if ($filter_brand) {
    $query .= " WHERE brand_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $filter_brand);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($query);
}

$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$brands = [];
$brandResult = $conn->query("SELECT DISTINCT brand_name FROM tbl_products");
while ($b = $brandResult->fetch_assoc()) {
    $brands[] = $b['brand_name'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Product Management</title>
  <link rel="stylesheet" href="navbar/navbar.css">
  <style>

    @font-face {
      font-family: titleFont;
      src: url(css/Pricedown_Bl.otf);
    }

    * {
      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }

    h1 {
      font-family: titleFont;
    }

    body::-webkit-scrollbar {
      display: none;
    }

    body {
      background-color: #f2f3f4;
      color: #213345;
    }

    .about-hero {
      background-color: #2c3e50;
      color: white;
      padding: 35px 35px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .about-hero h1 {
      margin-bottom: 10px;
      font-size: 2.5rem;
    }

    .about-hero img {
      width: 125px;
    }

    .tab-link {
      font-size: 18px;
    }

    .all {
      margin: 0 1%;
    }

    .admin-wrapper {
      display: flex;
      gap: 12px;
      margin: 0 auto;
    }

    .form-section {
      flex: 2;
      background: white;
      padding: 15px 30px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
    }

    .form-section label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: #2c3e50;
    }

    .form-section input[type="text"],
    .form-section input[type="number"],
    .form-section textarea,
    .form-section select {
      width: 100%;
      padding: 10px;
      margin-bottom: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
      resize: vertical;
    }

    .form-section button {
      background-color: #2c3e50;
      color: white;
      border: none;
      width: 100%;
      padding: 12px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin-top: 15px;
    }

    .form-section button:hover {
      background-color: #1a252f;
    }

    .product-section {
      flex: 4;
      background: white;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
      max-height: 80vh;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
    }

    .filter-bar {
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 15px 6px;
    }

    .filter-bar label {
      font-weight: 600;
      color: #2c3e50;
    }

    .filter-bar select {
      padding: 8px 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 14px;
      cursor: pointer;
    }

    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 15px;
    }

    .product-card {
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 15px;
      background-color: #fafafa;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
      transition: box-shadow 0.3s ease;
    }

    .product-card:hover {
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .product-image {
      height: 250px;
      margin-bottom: 10px;
      border-radius: 8px;
      background-color: white;
      align-self: center;
    }

    .product-name {
      font-weight: 700;
      margin-bottom: 6px;
      color: #2c3e50;
      font-family: titleFont;
      font-size: 20px;
    }

    .product-brand {
      font-weight: 600;
      font-size: 15px;
      margin-bottom: 6px;
      color: #556677;
    }

    .product-desc {
      font-size: 14px;
      color: #667788;
      margin-bottom: 10px;
      flex-grow: 1;
    }

    .product-price-stock {
      font-weight: 600;
      font-size: 13px;
      margin-bottom: 15px;
      color: #2c3e50;
    }

    .card-buttons {
      display: flex;
      gap: 10px;
    }

    .btn-edit,
    .btn-delete {
      flex: 1;
      padding: 8px 0;
      border-radius: 6px;
      border: none;
      font-weight: 600;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.3s ease;
      width: 100%;
      color: white;
    }

    .btn-edit {
      background-color: #2c3e50;
    }

    .btn-edit:hover {
      background-color: #1a252f;
    }

    .btn-delete {
      background-color: #e74c3c;
    }

    .btn-delete:hover {
      background-color: #b0302b;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      backdrop-filter: blur(3px);
      background-color: rgba(0, 0, 0, 0.4);
      justify-content: center;
      align-items: center;
      transition: opacity 0.3s ease;
    }

    .modal.active {
      display: flex;
    }

    .modal-content {
      background: #fff;
      border-radius: 16px;
      padding: 30px 25px;
      width: 100%;
      max-width: 500px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
      position: relative;
      animation: slideUp 0.3s ease;
      font-family: 'Segoe UI', sans-serif;
    }

    .modal-close {
      position: absolute;
      top: 18px;
      right: 20px;
      font-size: 26px;
      background: none;
      border: none;
      color: #666;
      cursor: pointer;
      transition: color 0.2s ease;
    }

    .modal-close:hover {
      color: #000;
    }

    .modal-content h3 {
      margin-top: 0;
      margin-bottom: 25px;
      font-size: 22px;
      color: #333;
      text-align: center;
      font-weight: 600;
    }

    .modal-content label {
      display: block;
      margin-bottom: 5px;
      margin-top: 15px;
      font-size: 14px;
      color: #555;
      font-weight: 500;
    }

    .modal-content input[type="text"],
    .modal-content input[type="number"],
    .modal-content input[type="file"],
    .modal-content textarea {
      width: 100%;
      padding: 10px 14px;
      border-radius: 10px;
      border: 1px solid #ccc;
      font-size: 15px;
      background: #fafafa;
      transition: border 0.3s ease;
    }

    .modal-content input:focus,
    .modal-content textarea:focus {
      border-color: #2c3e50;
      outline: none;
      background: #fff;
    }

    .modal-content button[type="submit"] {
      width: 100%;
      margin-top: 25px;
      background: #2c3e50;
      color: white;
      border: none;
      border-radius: 8px;
      padding: 12px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .modal-content button[type="submit"]:hover {
      background: #2c3e50;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>

<body>
  <section class="about-hero">
    <img src="images/logo.png" alt="Website Logo">
    <ul>
      <h1>Admin Dashboard</h1>
    </ul>
    <ul>
      <a href="logout.php" class="tab-link">
        <svg viewBox="0 0 24 24" aria-hidden="true" id="logout-icon">
          <path d="M16 13v-2H7V8l-5 4 5 4v-3h9zM20 3h-8v2h8v14h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z" />
        </svg>
        Logout
      </a>
    </ul>
  </section>
  <div class="all">
    <div class="admin-wrapper">

      <div class="form-section">
        <h2>Add Product</h2>
        <br>
        <form action="admin_actions.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="action" value="add" />
          <label for="name">Product Name</label>
          <input type="text" name="name" id="name" required />

          <label for="brand_name">Brand Name</label>
          <input type="text" name="brand_name" id="brand_name" required />

          <label for="description">Description</label>
          <textarea name="description" id="description" rows="3" required></textarea>

          <label for="price">Price</label>
          <input type="number" step="0.01" min="0" name="price" id="price" required />

          <label for="stock_quantity">Stock Quantity</label>
          <input type="number" min="0" name="stock_quantity" id="stock_quantity" required />

          <label for="image">Product Image</label>
          <input type="file" name="image" id="image" accept="image/*" required />

          <button type="submit">Add Product</button>
        </form>
      </div>

      <div class="product-section">
        <div class="filter-bar">
          <form id="filterForm" method="GET" action="admin_page.php" style="margin:0; display:flex; align-items:center; gap: 8px;">
            <label for="filter_brand">Filter by Brand:</label>
            <select name="filter_brand" id="filter_brand" onchange="document.getElementById('filterForm').submit()">
              <option value="">-- All Brands --</option>
              <?php foreach ($brands as $brand): ?>
              <option value="<?=htmlspecialchars($brand)?>" <?= $filter_brand === $brand ? 'selected' : '' ?>><?=htmlspecialchars($brand)?></option>
              <?php endforeach; ?>
            </select>
          </form>
        </div>

        <div class="products-grid">
          <?php if (count($products) === 0): ?>
          <p>No products found.</p>
          <?php endif; ?>
          <?php foreach ($products as $product): ?>
          <div class="product-card" data-id="<?= $product['product_id'] ?>" data-name="<?= htmlspecialchars($product['name']) ?>" data-brand="<?= htmlspecialchars($product['brand_name']) ?>" data-description="<?= htmlspecialchars($product['description']) ?>" data-price="<?= $product['price'] ?>" data-stock="<?= $product['stock_quantity'] ?>">
            <img src="images/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="product-image" />
            <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
            <div class="product-brand">Brand: <?= htmlspecialchars($product['brand_name']) ?></div>
            <div class="product-desc"><?= htmlspecialchars($product['description']) ?></div>
            <div class="product-price-stock">
              Price: ₱<?= number_format($product['price'], 2) ?><br />
              Stock: <?= $product['stock_quantity'] ?>
            </div>
            <div class="card-buttons">
              <button class="btn-edit" onclick="openEditModal(<?= $product['product_id'] ?>)">Edit</button>
              <form action="admin_actions.php" method="POST" style="flex:1; margin:0;">
                <input type="hidden" name="action" value="delete" />
                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>" />
                <button type="submit" class="btn-delete" onclick="return confirm('Delete this product?');">Delete</button>
              </form>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div id="editModal" class="modal">
      <div class="modal-content">
        <button class="modal-close" onclick="closeEditModal()">&times;</button>
        <h3>Edit Product</h3>
        <form id="editForm" action="admin_actions.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="action" value="edit" />
          <input type="hidden" name="product_id" id="edit_product_id" />

          <label for="edit_name">Product Name</label>
          <input type="text" name="name" id="edit_name" required />

          <label for="edit_brand_name">Brand Name</label>
          <input type="text" name="brand_name" id="edit_brand_name" required />

          <label for="edit_description">Description</label>
          <textarea name="description" id="edit_description" rows="3" required></textarea>

          <label for="edit_price">Price</label>
          <input type="number" step="0.01" min="0" name="price" id="edit_price" required />

          <label for="edit_stock_quantity">Stock Quantity</label>
          <input type="number" min="0" name="stock_quantity" id="edit_stock_quantity" required />

          <label for="edit_image">Product Image (leave empty to keep current)</label>
          <input type="file" name="image" id="edit_image" accept="images/*" />

          <button type="submit">Save Changes</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    function openEditModal(id) {
        const productCard = document.querySelector(`.product-card[data-id='${id}']`);
        if (!productCard) return;
    
        document.getElementById('edit_product_id').value = id;
        document.getElementById('edit_name').value = productCard.getAttribute('data-name');
        document.getElementById('edit_brand_name').value = productCard.getAttribute('data-brand');
        document.getElementById('edit_description').value = productCard.getAttribute('data-description');
        document.getElementById('edit_price').value = productCard.getAttribute('data-price');
        document.getElementById('edit_stock_quantity').value = productCard.getAttribute('data-stock');
    
        document.getElementById('editModal').classList.add('active');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
        document.getElementById('editForm').reset();
    }
  </script>

</body>

</html>