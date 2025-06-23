<?php

// Get the current page name for active link highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<link rel="stylesheet" href="../css/nav.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<header>
  <div class="container">
    <h1>Football Kits Nepal</h1>
    <nav>
      <ul>
        <li><a href="Home.php" <?php echo ($current_page == 'Home.php') ? 'class="active"' : ''; ?>>Home</a></li>
        <li><a href="Kits.php" <?php echo ($current_page == 'Kits.php') ? 'class="active"' : ''; ?>>Kits</a></li>
        <li><a href="aboutus.php" <?php echo ($current_page == 'aboutus.php') ? 'class="active"' : ''; ?>>About</a></li>
        <li>
          <a href="cart.php" class="cart-icon">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count" id="cart-count">0</span>
          </a>
        </li>
        <li><a href="userprofile.php" class="profile-btn">Profile</a></li>
        <li><a href="logout.php" class="logout-btn">Logout</a></li>
      </ul>
    </nav>
  </div>
</header> 