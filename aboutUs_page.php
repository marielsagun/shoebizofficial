<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>About Us | Shoe Biz</title>
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

    body {
      margin: 0;
      background: #f2f3f4;
      color: #213345;
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

    .about-hero p {
      font-size: 1.2rem;
    }

    .about-description {
      margin: 150px 10%;
      padding: 0 20px;
      font-size: 18px;
      line-height: 1.8;
      text-align: center;
    }

    .brands {
      background-color: #F3F2ED;
      padding: 20px;
      text-align: center;
    }

    .brands h2 {
      margin-bottom: 10px;
      color: #213345;
    }

    .brand-logos {
      display: flex;
      justify-content: center;
      gap: 30px;
      flex-wrap: wrap;
    }

    .brand-logos img {
      width: 85px;
      height: auto;
      filter: grayscale(100%);
      transition: 0.3s;
    }

    .brand-logos img:hover {
      filter: grayscale(0%);
      transform: scale(1.1);
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
  </style>
</head>

<body>

  <?php include 'navbar/navbar.php'; ?>

  <section class="about-hero">
    <h1>About Shoe Biz</h1>
    <p>Your One-Stop Store for Iconic Shoe Brands</p>
  </section>

  <section class="about-description">
    <p>At <strong>Shoe Biz</strong>, we bring together the most loved and trusted shoe brands under one digital roof. From high-performance sports footwear to trendy casual kicks, our store is built for people who want both variety and quality.</p>

    <p>We aim to make shopping seamless by offering collections that reflect comfort, authenticity, and modern style. Whether you're a sneakerhead, a trendsetter, or someone looking for everyday wear, Shoe Biz is here for every step.</p>
  </section>

  <section class="brands">
    <h2>Brands We Carry</h2>
    <div class="brand-logos">
      <img src="images/nike.png" alt="Nike">
      <img src="images/adidas.png" alt="Adidas">
      <img src="images/new_balance.png" alt="Converse">
      <img src="images/under_armour.png" alt="New Balance">
      <img src="images/world_balance.png" alt="Puma">
    </div>
  </section>

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

</body>

</html>