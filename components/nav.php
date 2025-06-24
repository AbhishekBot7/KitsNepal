<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the current page name for active link highlighting
$current_page = basename($_SERVER['PHP_SELF']);
$script_name = $_SERVER['SCRIPT_NAME'];

// Determine the base path based on the current script location
$is_index = (strpos($script_name, 'index.php') !== false);
$is_pages_dir = (strpos($script_name, '/pages/') !== false);

// Set base paths
$base_path = $is_index ? './' : ($is_pages_dir ? '../' : './');
$css_path = $base_path . 'css/';
$js_path = $base_path . 'js/';
$pages_path = $base_path . 'pages/';

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// Get user's first name for greeting
$first_name = '';
if ($is_logged_in && isset($_SESSION['fullname'])) {
    $fullname = explode(' ', $_SESSION['fullname']);
    $first_name = $fullname[0]; // Get first name
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Kits Nepal</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $css_path; ?>nav.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>
                <a href="<?php echo $base_path; ?>index.php">
                    <i class="fas fa-futbol"></i> Football Kits Nepal
                </a>
            </h1>

            <nav>
                <ul>
                    <li><a href="<?php echo $base_path; ?>index.php" class="<?php echo $current_page === 'index.php' ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="<?php echo $pages_path; ?>Kits.php" class="<?php echo $current_page === 'Kits.php' ? 'active' : ''; ?>">Shop</a></li>
                    <li><a href="<?php echo $pages_path; ?>aboutus.php" class="<?php echo $current_page === 'aboutus.php' ? 'active' : ''; ?>">About</a></li>
                    <li><a href="<?php echo $pages_path; ?>contact.php" class="<?php echo $current_page === 'contact.php' ? 'active' : ''; ?>">Contact</a></li>

                    <!-- Cart Icon -->
                    <li class="cart-item">
                        <a href="<?php echo $base_path; ?>pages/cart.php" class="cart-icon" aria-label="Shopping Cart">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count">0</span>
                        </a>
                    </li>

                    <!-- User Dropdown or Login/Register Buttons -->
                    <?php if ($is_logged_in): ?>
                        <li class="dropdown">
                            <a href="#" class="profile-btn" aria-expanded="false" aria-haspopup="true">
                                <i class="fas fa-user-circle"></i>
                                <span><?php echo htmlspecialchars($first_name); ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </a>
                            <div class="dropdown-content">
                                <a href="<?php echo $pages_path; ?>profile.php"><i class="fas fa-user"></i> My Profile</a>
                                <a href="<?php echo $pages_path; ?>orders.php"><i class="fas fa-box"></i> My Orders</a>
                                <a href="<?php echo $pages_path; ?>wishlist.php"><i class="fas fa-heart"></i> Wishlist</a>
                                <a href="<?php echo $pages_path; ?>settings.php"><i class="fas fa-cog"></i> Settings</a>
                                <a href="<?php echo $pages_path; ?>logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="<?php echo $pages_path; ?>login.php" class="login-btn">Login</a></li>
                        <li><a href="<?php echo $pages_path; ?>register.php" class="register-btn">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- JavaScript -->
    <script src="<?php echo $js_path; ?>nav.js"></script>
</body>
</html>
