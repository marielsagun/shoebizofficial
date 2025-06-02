

<nav class="tab-nav" aria-label="Primary Navigation">
  <ul>
    <li>
      <img src="images/logo.png" alt="Website Logo">
    </li>
    <li>
      <a href="home_page.php" class="tab-link">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        Home
      </a>
    </li>
    <li>
      <a href="aboutUs_page.php" class="tab-link">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        About Us
      </a>
    </li>
    <li>
      <a href="cart_page.php" class="tab-link">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2zM7.16 14.26l-.16-.74L5 6H3v2h1l3.6 7.59-1.35 2.45c-.15.27-.25.58-.25.91 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25 0-.06.02-.12.05-.17l1.1-2h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49-1.74-1-3.49 6.32H7.16z"/></svg>
        Cart
      </a>
    </li>
    <li>
      <?php if (isset($_SESSION['user_id'])): ?>
        <!-- User is logged in, show logout -->
        <a href="logout.php" class="tab-link">
          <svg viewBox="0 0 24 24" aria-hidden="true" id="logout-icon">
            <path d="M16 13v-2H7V8l-5 4 5 4v-3h9zM20 3h-8v2h8v14h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/>
          </svg>
          Logout
        </a>
      <?php else: ?>
        <!-- User is not logged in, show login -->
        <a href="login_page.php" class="tab-link">
          <svg viewBox="0 0 24 24" aria-hidden="true" id="login-icon">
            <path d="M10 17l5-5-5-5v10zM14 2H6c-1.1 0-2 .9-2 2v4h2V4h8v16H6v-4H4v4c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
          </svg>
          Login
        </a>
      <?php endif; ?>
    </li>
  </ul>
</nav>
